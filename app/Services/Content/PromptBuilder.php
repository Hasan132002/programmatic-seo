<?php

namespace App\Services\Content;

use App\Enums\NicheType;

/**
 * Builds AI prompts tailored to the site's niche type and page variables.
 *
 * Each niche type (city, comparison, directory, custom) produces a specialised
 * prompt that instructs the AI to generate SEO-optimised, unique HTML content
 * with the appropriate structure and focus.
 */
class PromptBuilder
{
    /**
     * Build a complete AI generation prompt for a page based on its niche type.
     *
     * @param NicheType $niche     The niche type of the site.
     * @param array     $variables The page's variable data for personalisation.
     *
     * @return string  The fully assembled prompt ready for AI generation.
     */
    public function build(NicheType $niche, array $variables): string
    {
        $variableContext = $this->formatVariableContext($variables);

        $nichePrompt = match ($niche) {
            NicheType::City => $this->buildCityPrompt($variables),
            NicheType::Comparison => $this->buildComparisonPrompt($variables),
            NicheType::Directory => $this->buildDirectoryPrompt($variables),
            NicheType::Custom => $this->buildCustomPrompt($variables),
        };

        return <<<PROMPT
{$nichePrompt}

AVAILABLE DATA:
{$variableContext}

IMPORTANT INSTRUCTIONS:
- Write in clean, semantic HTML (h2, h3, p, ul, ol, table, strong, em tags).
- Do NOT include <html>, <head>, <body>, or <doctype> tags.
- Make the content unique, engaging, and informative.
- Optimise for SEO: use natural keyword placement, answer user intent, include relevant headings.
- Aim for 800-1500 words of substantive content.
- Include a brief introduction and a conclusion or summary section.
- Use data provided in the variables to make content specific and accurate.
- Do NOT fabricate statistics or data not provided.
PROMPT;
    }

    /**
     * Build a prompt for a specific content section type.
     *
     * Useful for generating individual parts of a page (intro, FAQ, comparison table, etc.)
     * rather than the full page content.
     *
     * @param string $sectionType  The section identifier (e.g. 'intro', 'faq', 'features', 'comparison_table').
     * @param array  $context      Contextual data for the section.
     *
     * @return string  The section-specific prompt.
     */
    public function buildSection(string $sectionType, array $context): string
    {
        $contextStr = $this->formatVariableContext($context);

        return match ($sectionType) {
            'intro' => <<<PROMPT
Write an engaging introduction paragraph (2-3 sentences) in HTML for a page about the following topic.
Make it SEO-friendly and compelling to read further.

CONTEXT:
{$contextStr}

Output only the HTML content (use <p> tags).
PROMPT,

            'faq' => <<<PROMPT
Generate a FAQ section in HTML with 5-7 relevant questions and answers about the following topic.
Use <h3> for questions and <p> for answers. Wrap the entire section in a <div class="faq-section">.
Make answers detailed and helpful.

CONTEXT:
{$contextStr}
PROMPT,

            'features' => <<<PROMPT
Write a features or benefits section in HTML highlighting the key aspects of the following subject.
Use an <ul> or <ol> list with <li> items. Include brief descriptions for each feature.
Wrap in a <div class="features-section">.

CONTEXT:
{$contextStr}
PROMPT,

            'comparison_table' => <<<PROMPT
Create an HTML comparison table for the items described below.
Use a <table> with <thead> and <tbody>. Include relevant comparison criteria as columns.
Make it clear and easy to scan. Wrap in a <div class="comparison-table">.

CONTEXT:
{$contextStr}
PROMPT,

            'conclusion' => <<<PROMPT
Write a brief conclusion or summary section (2-4 sentences) in HTML for a page about the following topic.
Include a soft call-to-action where appropriate. Use <p> tags.

CONTEXT:
{$contextStr}
PROMPT,

            default => <<<PROMPT
Write an SEO-optimised HTML content section of type "{$sectionType}" based on the following context.
Use appropriate semantic HTML tags.

CONTEXT:
{$contextStr}
PROMPT,
        };
    }

    /**
     * Build the city/location-based niche prompt.
     */
    protected function buildCityPrompt(array $variables): string
    {
        $city = $variables['city_name'] ?? $variables['city'] ?? $variables['location'] ?? 'the city';
        $state = $variables['state'] ?? $variables['region'] ?? '';
        $topic = $variables['topic'] ?? $variables['service'] ?? $variables['keyword'] ?? 'local services';

        $locationLabel = $state ? "{$city}, {$state}" : $city;

        return <<<PROMPT
Write a comprehensive, locally-focused SEO article about "{$topic}" in {$locationLabel}.

The content should:
- Reference specific local details about {$locationLabel} where relevant.
- Include sections covering: overview, key information, local tips, and a FAQ section.
- Use location-specific language to rank well for "{$topic} in {$locationLabel}" searches.
- Feel authentic and locally informed, not generic.
PROMPT;
    }

    /**
     * Build the comparison niche prompt.
     */
    protected function buildComparisonPrompt(array $variables): string
    {
        $item1 = $variables['item_1'] ?? $variables['product_1'] ?? $variables['option_a'] ?? 'Option A';
        $item2 = $variables['item_2'] ?? $variables['product_2'] ?? $variables['option_b'] ?? 'Option B';
        $category = $variables['category'] ?? $variables['topic'] ?? 'products';

        return <<<PROMPT
Write a detailed, unbiased comparison article between "{$item1}" and "{$item2}" in the {$category} category.

The content should:
- Start with a brief overview of both options.
- Include a comparison table highlighting key differences (features, pricing, pros/cons).
- Provide detailed analysis of each option's strengths and weaknesses.
- Include a "Which one should you choose?" section with recommendations for different use cases.
- End with a FAQ section addressing common comparison questions.
- Remain objective and helpful — do not strongly favour one over the other without data to back it up.
PROMPT;
    }

    /**
     * Build the directory/listings niche prompt.
     */
    protected function buildDirectoryPrompt(array $variables): string
    {
        $listingName = $variables['name'] ?? $variables['business_name'] ?? $variables['listing_name'] ?? 'this listing';
        $category = $variables['category'] ?? $variables['type'] ?? 'business';
        $location = $variables['location'] ?? $variables['city'] ?? '';

        $locationClause = $location ? " in {$location}" : '';

        return <<<PROMPT
Write a detailed directory listing page for "{$listingName}", a {$category}{$locationClause}.

The content should:
- Provide a professional overview and description of the listing.
- Include key details: services offered, specialities, and notable features.
- Add a "What to Expect" or "Key Information" section.
- Include any relevant local context if location data is available.
- Add a brief FAQ section with 3-5 common questions visitors might have.
- Sound informative and trustworthy, like a well-curated directory entry.
PROMPT;
    }

    /**
     * Build the custom/generic niche prompt.
     */
    protected function buildCustomPrompt(array $variables): string
    {
        $title = $variables['title'] ?? $variables['topic'] ?? $variables['keyword'] ?? 'the topic';
        $description = $variables['description'] ?? $variables['summary'] ?? '';

        $descriptionClause = $description ? "\nAdditional context: {$description}" : '';

        return <<<PROMPT
Write a comprehensive, SEO-optimised article about "{$title}".{$descriptionClause}

The content should:
- Be well-structured with clear headings (H2, H3) and logical flow.
- Cover the topic thoroughly with actionable information.
- Include an introduction, main content sections, and a conclusion.
- Add a FAQ section with 3-5 relevant questions and answers.
- Target relevant search queries naturally without keyword stuffing.
PROMPT;
    }

    /**
     * Format an array of variables as a readable string for the AI prompt.
     */
    protected function formatVariableContext(array $variables): string
    {
        $lines = [];

        foreach ($variables as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }

            $lines[] = "- {$key}: {$value}";
        }

        return implode("\n", $lines);
    }
}
