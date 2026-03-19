<?php

namespace App\Services\SEO;

use App\Enums\NicheType;
use App\Models\Page;

/**
 * Builds JSON-LD structured data (schema.org) markup for pages.
 *
 * Always includes WebPage and BreadcrumbList schemas. Adds niche-specific
 * schemas: LocalBusiness (city), Product (comparison), ItemList (directory).
 * Detects FAQ content patterns and adds FAQPage schema when applicable.
 */
class SchemaMarkupBuilder
{
    /**
     * Generate the complete JSON-LD schema array for a page.
     *
     * Returns a schema.org graph with multiple schema types appropriate
     * for the page's niche and content.
     *
     * @param Page $page  The page to generate schema for.
     *
     * @return array  The JSON-LD schema array with @context and @graph.
     */
    public function generate(Page $page): array
    {
        $site = $page->site;
        $schemas = [];

        // Always include WebPage schema
        $schemas[] = $this->buildWebPageSchema($page);

        // Always include BreadcrumbList
        $schemas[] = $this->buildBreadcrumbSchema($page);

        // Add niche-specific schemas
        $nicheType = $site->niche_type;

        if ($nicheType === NicheType::City) {
            $schemas[] = $this->buildLocalBusinessSchema($page);
        }

        if ($nicheType === NicheType::Comparison) {
            $schemas[] = $this->buildProductSchema($page);
        }

        if ($nicheType === NicheType::Directory) {
            $schemas[] = $this->buildItemListSchema($page);
        }

        // Detect and add FAQ schema if content contains FAQ patterns
        $faqSchema = $this->buildFaqSchema($page);
        if ($faqSchema !== null) {
            $schemas[] = $faqSchema;
        }

        return [
            '@context' => 'https://schema.org',
            '@graph'   => $schemas,
        ];
    }

    /**
     * Build the WebPage schema type.
     */
    protected function buildWebPageSchema(Page $page): array
    {
        $site = $page->site;

        $schema = [
            '@type'       => 'WebPage',
            '@id'         => $page->full_url . '#webpage',
            'url'         => $page->full_url,
            'name'        => $page->meta_title ?? $page->title,
            'isPartOf'    => [
                '@id' => $site->url . '#website',
            ],
            'datePublished'  => $page->published_at?->toIso8601String(),
            'dateModified'   => $page->updated_at?->toIso8601String(),
        ];

        if (!empty($page->meta_description)) {
            $schema['description'] = $page->meta_description;
        }

        if (!empty($page->og_image)) {
            $schema['primaryImageOfPage'] = [
                '@type' => 'ImageObject',
                'url'   => $page->og_image,
            ];
        }

        return $schema;
    }

    /**
     * Build the BreadcrumbList schema type.
     */
    protected function buildBreadcrumbSchema(Page $page): array
    {
        $site = $page->site;

        return [
            '@type'           => 'BreadcrumbList',
            '@id'             => $page->full_url . '#breadcrumb',
            'itemListElement' => [
                [
                    '@type'    => 'ListItem',
                    'position' => 1,
                    'name'     => 'Home',
                    'item'     => $site->url,
                ],
                [
                    '@type'    => 'ListItem',
                    'position' => 2,
                    'name'     => $page->title,
                    'item'     => $page->full_url,
                ],
            ],
        ];
    }

    /**
     * Build a LocalBusiness schema type for city/location niche pages.
     */
    protected function buildLocalBusinessSchema(Page $page): array
    {
        $variables = $page->variable_data ?? [];

        $schema = [
            '@type' => 'LocalBusiness',
            '@id'   => $page->full_url . '#localbusiness',
            'name'  => $variables['business_name'] ?? $page->title,
        ];

        if (!empty($variables['city_name']) || !empty($variables['city'])) {
            $city = $variables['city_name'] ?? $variables['city'];
            $state = $variables['state'] ?? $variables['region'] ?? '';
            $schema['address'] = [
                '@type'           => 'PostalAddress',
                'addressLocality' => $city,
            ];
            if ($state) {
                $schema['address']['addressRegion'] = $state;
            }
        }

        if (!empty($variables['description'])) {
            $schema['description'] = $variables['description'];
        }

        if (!empty($variables['phone'])) {
            $schema['telephone'] = $variables['phone'];
        }

        return $schema;
    }

    /**
     * Build a Product schema type for comparison niche pages.
     */
    protected function buildProductSchema(Page $page): array
    {
        $variables = $page->variable_data ?? [];

        $schema = [
            '@type'       => 'Product',
            '@id'         => $page->full_url . '#product',
            'name'        => $variables['item_1'] ?? $variables['product_1'] ?? $page->title,
            'description' => $page->meta_description ?? strip_tags($page->title),
        ];

        if (!empty($variables['category'])) {
            $schema['category'] = $variables['category'];
        }

        if (!empty($variables['brand'])) {
            $schema['brand'] = [
                '@type' => 'Brand',
                'name'  => $variables['brand'],
            ];
        }

        return $schema;
    }

    /**
     * Build an ItemList schema type for directory/listing niche pages.
     */
    protected function buildItemListSchema(Page $page): array
    {
        $variables = $page->variable_data ?? [];
        $items = $variables['items'] ?? $variables['listings'] ?? [];

        $itemElements = [];
        $position = 1;

        if (is_array($items)) {
            foreach ($items as $item) {
                $name = is_array($item) ? ($item['name'] ?? $item['title'] ?? "Item {$position}") : (string) $item;
                $itemElements[] = [
                    '@type'    => 'ListItem',
                    'position' => $position,
                    'name'     => $name,
                ];
                $position++;
            }
        }

        // If no items array, create a single-item list from page data
        if (empty($itemElements)) {
            $itemElements[] = [
                '@type'    => 'ListItem',
                'position' => 1,
                'name'     => $variables['name'] ?? $variables['listing_name'] ?? $page->title,
            ];
        }

        return [
            '@type'           => 'ItemList',
            '@id'             => $page->full_url . '#itemlist',
            'name'            => $page->title,
            'itemListElement' => $itemElements,
        ];
    }

    /**
     * Build a FAQPage schema if the content contains FAQ patterns.
     *
     * Detects FAQ sections by looking for heading+paragraph patterns
     * that follow common FAQ formatting conventions.
     *
     * @param Page $page  The page to inspect for FAQ content.
     *
     * @return array|null  The FAQPage schema or null if no FAQ detected.
     */
    protected function buildFaqSchema(Page $page): ?array
    {
        $html = $page->content_html ?? '';

        if (empty($html)) {
            return null;
        }

        // Look for FAQ patterns: h3 questions followed by paragraph answers
        $pattern = '/<h[23][^>]*>\s*(.*?)\s*<\/h[23]>\s*<p[^>]*>\s*(.*?)\s*<\/p>/si';

        if (!preg_match_all($pattern, $html, $matches, PREG_SET_ORDER)) {
            return null;
        }

        // Filter to only question-like headings (contain "?" or start with question words)
        $questionWords = ['what', 'how', 'why', 'when', 'where', 'who', 'which', 'can', 'do', 'does', 'is', 'are', 'should'];
        $faqItems = [];

        foreach ($matches as $match) {
            $question = strip_tags(trim($match[1]));
            $answer = strip_tags(trim($match[2]));

            if (empty($question) || empty($answer)) {
                continue;
            }

            $isQuestion = str_contains($question, '?');
            if (!$isQuestion) {
                $firstWord = strtolower(explode(' ', $question)[0]);
                $isQuestion = in_array($firstWord, $questionWords, true);
            }

            if ($isQuestion) {
                $faqItems[] = [
                    '@type'          => 'Question',
                    'name'           => $question,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text'  => $answer,
                    ],
                ];
            }
        }

        if (empty($faqItems)) {
            return null;
        }

        return [
            '@type'      => 'FAQPage',
            '@id'        => $page->full_url . '#faq',
            'mainEntity' => $faqItems,
        ];
    }
}
