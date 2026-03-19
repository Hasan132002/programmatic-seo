<?php

namespace App\Services\Builder;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Registry for GrapesJS builder blocks (reusable page components).
 *
 * Manages system-defined and user-created builder blocks that appear
 * in the page editor's component panel. Blocks are categorised for
 * easy discovery (e.g. layout, content, seo, media).
 */
class BlockRegistry
{
    /**
     * Retrieve all system-defined builder blocks.
     *
     * System blocks are the default set of blocks available to all users.
     * They cover common page sections like headers, content areas, CTAs, etc.
     *
     * @return Collection  Collection of block definition arrays.
     */
    public function getSystemBlocks(): Collection
    {
        return collect($this->defaultBlocks());
    }

    /**
     * Retrieve builder blocks filtered by category.
     *
     * @param string $category  The category to filter by (e.g. 'layout', 'content', 'seo', 'media').
     *
     * @return Collection  Filtered collection of block definition arrays.
     */
    public function getBlocksByCategory(string $category): Collection
    {
        return $this->getSystemBlocks()->filter(
            fn (array $block) => ($block['category'] ?? '') === $category,
        )->values();
    }

    /**
     * Register a new custom builder block.
     *
     * Stores the block definition in the database for retrieval by the
     * page editor. If a builder_blocks table exists, uses it; otherwise
     * returns the data as-is for in-memory use.
     *
     * @param array $data  Block definition with keys: label, category, content, attributes, media.
     *
     * @return array  The registered block data (with id if persisted).
     */
    public function registerBlock(array $data): array
    {
        $block = [
            'label'      => $data['label'] ?? 'Custom Block',
            'category'   => $data['category'] ?? 'custom',
            'content'    => $data['content'] ?? '',
            'attributes' => $data['attributes'] ?? [],
            'media'      => $data['media'] ?? '',
            'is_system'  => false,
        ];

        // Attempt to persist to database if table exists
        try {
            if (\Schema::hasTable('builder_blocks')) {
                $id = DB::table('builder_blocks')->insertGetId([
                    'label'      => $block['label'],
                    'category'   => $block['category'],
                    'content'    => is_array($block['content']) ? json_encode($block['content']) : $block['content'],
                    'attributes' => json_encode($block['attributes']),
                    'media'      => $block['media'],
                    'is_system'  => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $block['id'] = $id;
            }
        } catch (\Throwable) {
            // Table doesn't exist yet — return in-memory block
        }

        return $block;
    }

    /**
     * Get the default set of system builder blocks.
     *
     * @return array<int, array>  Array of block definition arrays.
     */
    protected function defaultBlocks(): array
    {
        return [
            // Layout blocks
            [
                'label'    => 'Section',
                'category' => 'layout',
                'content'  => '<section class="section-block"><div class="container">{{content}}</div></section>',
                'media'    => '<svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2" fill="none" stroke="currentColor" stroke-width="2"/></svg>',
                'attributes' => ['class' => 'gjs-block-section'],
            ],
            [
                'label'    => 'Two Columns',
                'category' => 'layout',
                'content'  => '<div class="row"><div class="col col-6">{{left_content}}</div><div class="col col-6">{{right_content}}</div></div>',
                'media'    => '<svg viewBox="0 0 24 24"><rect x="2" y="4" width="9" height="16" rx="1" fill="none" stroke="currentColor" stroke-width="2"/><rect x="13" y="4" width="9" height="16" rx="1" fill="none" stroke="currentColor" stroke-width="2"/></svg>',
                'attributes' => ['class' => 'gjs-block-columns'],
            ],
            [
                'label'    => 'Three Columns',
                'category' => 'layout',
                'content'  => '<div class="row"><div class="col col-4">{{col1}}</div><div class="col col-4">{{col2}}</div><div class="col col-4">{{col3}}</div></div>',
                'media'    => '<svg viewBox="0 0 24 24"><rect x="1" y="4" width="6" height="16" rx="1" fill="none" stroke="currentColor" stroke-width="2"/><rect x="9" y="4" width="6" height="16" rx="1" fill="none" stroke="currentColor" stroke-width="2"/><rect x="17" y="4" width="6" height="16" rx="1" fill="none" stroke="currentColor" stroke-width="2"/></svg>',
                'attributes' => ['class' => 'gjs-block-3columns'],
            ],

            // Content blocks
            [
                'label'    => 'Heading',
                'category' => 'content',
                'content'  => '<h2>{{heading}}</h2>',
                'media'    => '<svg viewBox="0 0 24 24"><text x="3" y="18" font-size="18" font-weight="bold" fill="currentColor">H</text></svg>',
                'attributes' => ['class' => 'gjs-block-heading'],
            ],
            [
                'label'    => 'Text Block',
                'category' => 'content',
                'content'  => '<div class="text-block"><p>{{text_content}}</p></div>',
                'media'    => '<svg viewBox="0 0 24 24"><line x1="3" y1="6" x2="21" y2="6" stroke="currentColor" stroke-width="2"/><line x1="3" y1="12" x2="21" y2="12" stroke="currentColor" stroke-width="2"/><line x1="3" y1="18" x2="15" y2="18" stroke="currentColor" stroke-width="2"/></svg>',
                'attributes' => ['class' => 'gjs-block-text'],
            ],
            [
                'label'    => 'Image',
                'category' => 'content',
                'content'  => '<img src="{{image_url}}" alt="{{image_alt}}" class="img-responsive" />',
                'media'    => '<svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="8" cy="8" r="2" fill="currentColor"/><path d="M21 15l-5-5-8 8" stroke="currentColor" stroke-width="2" fill="none"/></svg>',
                'attributes' => ['class' => 'gjs-block-image'],
            ],
            [
                'label'    => 'List',
                'category' => 'content',
                'content'  => '<ul class="content-list"><li>{{item_1}}</li><li>{{item_2}}</li><li>{{item_3}}</li></ul>',
                'media'    => '<svg viewBox="0 0 24 24"><circle cx="4" cy="6" r="2" fill="currentColor"/><line x1="9" y1="6" x2="21" y2="6" stroke="currentColor" stroke-width="2"/><circle cx="4" cy="12" r="2" fill="currentColor"/><line x1="9" y1="12" x2="21" y2="12" stroke="currentColor" stroke-width="2"/><circle cx="4" cy="18" r="2" fill="currentColor"/><line x1="9" y1="18" x2="21" y2="18" stroke="currentColor" stroke-width="2"/></svg>',
                'attributes' => ['class' => 'gjs-block-list'],
            ],

            // SEO blocks
            [
                'label'    => 'FAQ Section',
                'category' => 'seo',
                'content'  => '<div class="faq-section"><h2>Frequently Asked Questions</h2><div class="faq-item"><h3>{{faq_q1}}</h3><p>{{faq_a1}}</p></div><div class="faq-item"><h3>{{faq_q2}}</h3><p>{{faq_a2}}</p></div><div class="faq-item"><h3>{{faq_q3}}</h3><p>{{faq_a3}}</p></div></div>',
                'media'    => '<svg viewBox="0 0 24 24"><text x="6" y="18" font-size="18" font-weight="bold" fill="currentColor">?</text></svg>',
                'attributes' => ['class' => 'gjs-block-faq'],
            ],
            [
                'label'    => 'Comparison Table',
                'category' => 'seo',
                'content'  => '<div class="comparison-table"><table><thead><tr><th>Feature</th><th>{{option_a}}</th><th>{{option_b}}</th></tr></thead><tbody><tr><td>{{feature_1}}</td><td>{{value_a1}}</td><td>{{value_b1}}</td></tr><tr><td>{{feature_2}}</td><td>{{value_a2}}</td><td>{{value_b2}}</td></tr></tbody></table></div>',
                'media'    => '<svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" fill="none" stroke="currentColor" stroke-width="2"/><line x1="3" y1="9" x2="21" y2="9" stroke="currentColor" stroke-width="1"/><line x1="12" y1="3" x2="12" y2="21" stroke="currentColor" stroke-width="1"/></svg>',
                'attributes' => ['class' => 'gjs-block-comparison'],
            ],
            [
                'label'    => 'CTA Button',
                'category' => 'seo',
                'content'  => '<div class="cta-section"><a href="{{cta_url}}" class="btn btn-primary btn-lg">{{cta_text}}</a></div>',
                'media'    => '<svg viewBox="0 0 24 24"><rect x="3" y="7" width="18" height="10" rx="5" fill="none" stroke="currentColor" stroke-width="2"/></svg>',
                'attributes' => ['class' => 'gjs-block-cta'],
            ],

            // Media blocks
            [
                'label'    => 'Video Embed',
                'category' => 'media',
                'content'  => '<div class="video-container"><iframe src="{{video_url}}" frameborder="0" allowfullscreen style="width:100%;aspect-ratio:16/9;"></iframe></div>',
                'media'    => '<svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2" fill="none" stroke="currentColor" stroke-width="2"/><polygon points="10,8 16,12 10,16" fill="currentColor"/></svg>',
                'attributes' => ['class' => 'gjs-block-video'],
            ],
            [
                'label'    => 'Map Embed',
                'category' => 'media',
                'content'  => '<div class="map-container"><iframe src="https://maps.google.com/maps?q={{map_location}}&output=embed" style="width:100%;height:400px;border:0;" allowfullscreen loading="lazy"></iframe></div>',
                'media'    => '<svg viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="9" r="2.5" fill="currentColor"/></svg>',
                'attributes' => ['class' => 'gjs-block-map'],
            ],
        ];
    }
}
