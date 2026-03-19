<?php

namespace App\Services\Content;

use App\Enums\ContentStatus;
use App\Enums\GenerationMethod;
use App\Models\Page;
use App\Services\AI\AIServiceFactory;
use App\Services\AI\AIServiceInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Main orchestrator for the content generation pipeline.
 *
 * Coordinates template rendering, AI generation, and hybrid approaches
 * to produce the final content HTML, meta tags, and publish the page.
 */
class ContentPipeline
{
    protected TemplateEngine $templateEngine;
    protected PromptBuilder $promptBuilder;
    protected AIServiceInterface $aiService;

    public function __construct(
        ?TemplateEngine $templateEngine = null,
        ?PromptBuilder $promptBuilder = null,
        ?AIServiceInterface $aiService = null,
    ) {
        $this->templateEngine = $templateEngine ?? new TemplateEngine();
        $this->promptBuilder = $promptBuilder ?? new PromptBuilder();
        $this->aiService = $aiService ?? AIServiceFactory::make();
    }

    /**
     * Generate content for a page and publish it.
     *
     * Pipeline steps:
     *  1. Determine the generation method from the page model.
     *  2. Gather variables from page.variable_data and any related data entries.
     *  3. Generate content based on the method (template, ai, hybrid, or manual).
     *  4. Set content_html, meta_title, and meta_description on the page.
     *  5. Update status to 'published' and set published_at timestamp.
     *  6. Save the page.
     *
     * @param Page $page  The page to generate content for.
     *
     * @throws RuntimeException If content generation fails.
     */
    public function generate(Page $page): void
    {
        $method = $page->generation_method ?? GenerationMethod::Manual;
        $variables = $this->gatherVariables($page);

        $contentHtml = match ($method) {
            GenerationMethod::Template => $this->generateFromTemplate($page, $variables),
            GenerationMethod::AI       => $this->generateFromAI($page, $variables),
            GenerationMethod::Hybrid   => $this->generateHybrid($page, $variables),
            GenerationMethod::Manual   => $page->content_html ?? '',
        };

        // Set content on the page
        $page->content_html = $contentHtml;

        // Generate meta tags if not already set
        if (empty($page->meta_title)) {
            $page->meta_title = $this->generateMetaTitle($page, $variables);
        }

        if (empty($page->meta_description)) {
            $page->meta_description = $this->generateMetaDescription($contentHtml, $page, $variables);
        }

        // Publish the page
        $page->status = ContentStatus::Published;
        $page->published_at = $page->published_at ?? Carbon::now();

        $page->save();
    }

    /**
     * Gather all variables for a page from its own variable_data and related data entries.
     *
     * @param Page $page  The page model.
     *
     * @return array  Merged variable data.
     */
    protected function gatherVariables(Page $page): array
    {
        $variables = $page->variable_data ?? [];

        // Merge page-level data
        $variables['page_title'] = $variables['page_title'] ?? $page->title;
        $variables['page_slug'] = $variables['page_slug'] ?? $page->slug;

        // Merge data from related data entries if the relationship exists
        if (method_exists($page, 'dataEntries')) {
            $page->loadMissing('dataEntries');
            foreach ($page->dataEntries ?? [] as $entry) {
                $entryData = $entry->data ?? [];
                $variables = array_merge($variables, $entryData);
            }
        }

        return $variables;
    }

    /**
     * Generate content using only the template engine.
     *
     * Requires the page to have a related template with layout_html.
     *
     * @param Page  $page       The page model.
     * @param array $variables  The merged variables.
     *
     * @return string  Rendered HTML content.
     */
    protected function generateFromTemplate(Page $page, array $variables): string
    {
        $templateHtml = $this->resolveTemplateHtml($page);

        if (empty($templateHtml)) {
            throw new RuntimeException("No template HTML found for page [{$page->id}].");
        }

        return $this->templateEngine->render($templateHtml, $variables);
    }

    /**
     * Generate content using the AI service.
     *
     * @param Page  $page       The page model.
     * @param array $variables  The merged variables.
     *
     * @return string  AI-generated HTML content.
     */
    protected function generateFromAI(Page $page, array $variables): string
    {
        $nicheType = $page->site->niche_type;

        $prompt = $this->promptBuilder->build($nicheType, $variables);

        $response = $this->aiService->generate($prompt);

        return $response->content;
    }

    /**
     * Generate content using a hybrid approach: template first, then AI for {{ai_*}} placeholders.
     *
     * Any template variable prefixed with "ai_" will be generated via the AI service.
     *
     * @param Page  $page       The page model.
     * @param array $variables  The merged variables.
     *
     * @return string  Hybrid-rendered HTML content.
     */
    protected function generateHybrid(Page $page, array $variables): string
    {
        $templateHtml = $this->resolveTemplateHtml($page);

        if (empty($templateHtml)) {
            // Fall back to full AI generation if no template
            return $this->generateFromAI($page, $variables);
        }

        // Extract AI placeholder variables from the template
        $allVariables = $this->templateEngine->extractVariables($templateHtml);
        $aiVariables = array_filter($allVariables, fn (string $name) => str_starts_with($name, 'ai_'));

        // Generate AI content for each ai_ placeholder
        $nicheType = $page->site->niche_type;

        foreach ($aiVariables as $varName) {
            if (isset($variables[$varName]) && !empty($variables[$varName])) {
                continue; // Already has a value
            }

            $sectionType = Str::after($varName, 'ai_');
            $sectionPrompt = $this->promptBuilder->buildSection($sectionType, $variables);
            $response = $this->aiService->generate($sectionPrompt, [
                'max_tokens' => 2000,
            ]);

            $variables[$varName] = $response->content;
        }

        return $this->templateEngine->render($templateHtml, $variables);
    }

    /**
     * Resolve the template HTML for a page.
     *
     * Checks the page's template relationship first, then falls back to content_json.
     *
     * @param Page $page  The page model.
     *
     * @return string|null  The template HTML or null if not available.
     */
    protected function resolveTemplateHtml(Page $page): ?string
    {
        // Try the associated page template
        if (method_exists($page, 'template') && $page->template) {
            return $page->template->layout_html;
        }

        // Fall back to content stored in content_json (GrapesJS data)
        if (!empty($page->content_json)) {
            $json = is_string($page->content_json) ? $page->content_json : json_encode($page->content_json);
            $data = json_decode($json, true);
            return $data['html'] ?? null;
        }

        return null;
    }

    /**
     * Generate a meta title from page data and variables.
     *
     * @param Page  $page       The page model.
     * @param array $variables  The merged variables.
     *
     * @return string  The generated meta title (max 70 chars).
     */
    protected function generateMetaTitle(Page $page, array $variables): string
    {
        $siteSeoDefaults = $page->site->seo_defaults ?? [];
        $titleSuffix = $siteSeoDefaults['title_suffix'] ?? $page->site->name;

        $title = $page->title;

        $fullTitle = "{$title} | {$titleSuffix}";

        if (strlen($fullTitle) > 70) {
            return Str::limit($title, 67, '...');
        }

        return $fullTitle;
    }

    /**
     * Generate a meta description from content or variables.
     *
     * @param string $contentHtml  The generated content HTML.
     * @param Page   $page         The page model.
     * @param array  $variables    The merged variables.
     *
     * @return string  The generated meta description (max 160 chars).
     */
    protected function generateMetaDescription(string $contentHtml, Page $page, array $variables): string
    {
        // Try to get description from variables
        if (!empty($variables['meta_description'])) {
            return Str::limit(strip_tags($variables['meta_description']), 160, '...');
        }

        // Try to get description from SEO defaults
        $siteSeoDefaults = $page->site->seo_defaults ?? [];
        if (!empty($siteSeoDefaults['description_template'])) {
            $template = $siteSeoDefaults['description_template'];
            $rendered = $this->templateEngine->render($template, $variables);
            return Str::limit(strip_tags($rendered), 160, '...');
        }

        // Fall back to first 160 chars of content
        $plainText = strip_tags($contentHtml);
        $plainText = preg_replace('/\s+/', ' ', trim($plainText));

        return Str::limit($plainText, 160, '...');
    }
}
