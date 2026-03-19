<?php

namespace App\Services\SEO;

use App\Models\Page;
use App\Services\Content\TemplateEngine;
use Illuminate\Support\Str;

/**
 * Builds meta tags (title, description, canonical URL) for pages.
 *
 * Uses page data combined with site-level SEO defaults to produce
 * well-formed meta tags within standard length limits.
 */
class MetaTagBuilder
{
    protected TemplateEngine $templateEngine;

    public function __construct(?TemplateEngine $templateEngine = null)
    {
        $this->templateEngine = $templateEngine ?? new TemplateEngine();
    }

    /**
     * Generate the meta title for a page.
     *
     * Uses the page's meta_title if set, otherwise builds from the page title
     * and the site's SEO default title suffix. Keeps within 70 characters.
     *
     * @param Page $page  The page model.
     *
     * @return string  The meta title string.
     */
    public function generateTitle(Page $page): string
    {
        // Use explicitly set meta title first
        if (!empty($page->meta_title)) {
            return Str::limit($page->meta_title, 70, '');
        }

        $seoDefaults = $page->site->seo_defaults ?? [];
        $variables = array_merge($page->variable_data ?? [], [
            'page_title' => $page->title,
            'site_name'  => $page->site->name,
        ]);

        // Use title template from SEO defaults if available
        if (!empty($seoDefaults['title_template'])) {
            $title = $this->templateEngine->render($seoDefaults['title_template'], $variables);
            return Str::limit($title, 70, '');
        }

        // Default: "Page Title | Site Name"
        $suffix = $seoDefaults['title_suffix'] ?? $page->site->name;
        $fullTitle = "{$page->title} | {$suffix}";

        if (strlen($fullTitle) > 70) {
            return Str::limit($page->title, 67, '...');
        }

        return $fullTitle;
    }

    /**
     * Generate the meta description for a page.
     *
     * Uses the page's meta_description if set, otherwise builds from the site
     * SEO defaults or strips the page's HTML content. Limited to 160 characters.
     *
     * @param Page $page  The page model.
     *
     * @return string  The meta description string (max 160 chars).
     */
    public function generateDescription(Page $page): string
    {
        // Use explicitly set meta description first
        if (!empty($page->meta_description)) {
            return Str::limit(strip_tags($page->meta_description), 160, '...');
        }

        $seoDefaults = $page->site->seo_defaults ?? [];
        $variables = array_merge($page->variable_data ?? [], [
            'page_title' => $page->title,
            'site_name'  => $page->site->name,
        ]);

        // Use description template from SEO defaults if available
        if (!empty($seoDefaults['description_template'])) {
            $description = $this->templateEngine->render($seoDefaults['description_template'], $variables);
            return Str::limit(strip_tags($description), 160, '...');
        }

        // Fall back to stripping HTML from the content
        if (!empty($page->content_html)) {
            $plainText = strip_tags($page->content_html);
            $plainText = preg_replace('/\s+/', ' ', trim($plainText));
            return Str::limit($plainText, 160, '...');
        }

        // Last resort: use the page title as description
        return Str::limit($page->title, 160, '...');
    }

    /**
     * Generate the canonical URL for a page.
     *
     * Uses the page's canonical_url if explicitly set, otherwise constructs
     * it from the site URL and page slug.
     *
     * @param Page $page  The page model.
     *
     * @return string  The canonical URL.
     */
    public function generateCanonical(Page $page): string
    {
        if (!empty($page->canonical_url)) {
            return $page->canonical_url;
        }

        return $page->site->url . '/' . ltrim($page->slug, '/');
    }
}
