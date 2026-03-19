<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Enums\DataSourceType;
use App\Enums\GenerationMethod;
use App\Enums\NicheType;
use App\Models\AdPlacement;
use App\Models\AffiliateLink;
use App\Models\DataEntry;
use App\Models\DataSource;
use App\Models\InternalLink;
use App\Models\Page;
use App\Models\PageTemplate;
use App\Models\Site;
use App\Models\User;
use Illuminate\Database\Seeder;

class SampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@admin.com')->first();

        if (! $admin) {
            $this->command->warn('Admin user not found. Skipping SampleDataSeeder.');
            return;
        }

        $citySite = $this->createCitySite($admin);
        $comparisonSite = $this->createComparisonSite($admin);

        $cityDataSource = $this->createCityDataSource($admin, $citySite);
        $comparisonDataSource = $this->createComparisonDataSource($admin, $comparisonSite);

        $this->createCityDataEntries($admin, $cityDataSource);
        $this->createComparisonDataEntries($admin, $comparisonDataSource);

        $cityTemplate = PageTemplate::where('slug', 'city-services')->where('is_system', true)->first();
        $comparisonTemplate = PageTemplate::where('slug', 'product-comparison')->where('is_system', true)->first();

        $cityPages = $this->createCityPages($admin, $citySite, $cityTemplate);
        $comparisonPages = $this->createComparisonPages($admin, $comparisonSite, $comparisonTemplate);

        $this->createInternalLinks($citySite, $cityPages);
        $this->createInternalLinks($comparisonSite, $comparisonPages);

        $this->createAdPlacements($admin, $citySite);
        $this->createAdPlacements($admin, $comparisonSite);

        $this->createAffiliateLinks($admin, $citySite);
        $this->createAffiliateLinks($admin, $comparisonSite);
    }

    private function createCitySite(User $admin): Site
    {
        return Site::updateOrCreate(
            ['tenant_id' => $admin->id, 'slug' => 'best-plumbers-usa'],
            [
                'name' => 'Best Plumbers USA',
                'domain' => null,
                'subdomain' => 'best-plumbers',
                'niche_type' => NicheType::City,
                'settings' => [
                    'theme' => 'modern',
                    'primary_color' => '#667eea',
                    'font_family' => 'Inter, system-ui, sans-serif',
                    'show_breadcrumbs' => true,
                    'show_sidebar' => false,
                ],
                'seo_defaults' => [
                    'title_template' => 'Best Plumbers in {{city_name}}, {{state}} | Top Rated 2026',
                    'description_template' => 'Find the best plumbers in {{city_name}}, {{state}}. Compare ratings, prices, and reviews for top-rated plumbing services near you.',
                    'robots' => 'index, follow',
                    'og_type' => 'website',
                ],
                'adsense_publisher_id' => 'ca-pub-1234567890123456',
                'is_published' => true,
            ]
        );
    }

    private function createComparisonSite(User $admin): Site
    {
        return Site::updateOrCreate(
            ['tenant_id' => $admin->id, 'slug' => 'tech-compare-hub'],
            [
                'name' => 'Tech Compare Hub',
                'domain' => null,
                'subdomain' => 'tech-compare',
                'niche_type' => NicheType::Comparison,
                'settings' => [
                    'theme' => 'clean',
                    'primary_color' => '#4f46e5',
                    'font_family' => 'Plus Jakarta Sans, system-ui, sans-serif',
                    'show_breadcrumbs' => true,
                    'show_sidebar' => true,
                ],
                'seo_defaults' => [
                    'title_template' => '{{product_a}} vs {{product_b}} - Detailed {{category}} Comparison 2026',
                    'description_template' => 'Compare {{product_a}} vs {{product_b}}. See detailed side-by-side comparison of features, pricing, and user ratings for {{category}}.',
                    'robots' => 'index, follow',
                    'og_type' => 'article',
                ],
                'adsense_publisher_id' => 'ca-pub-9876543210987654',
                'is_published' => true,
            ]
        );
    }

    private function createCityDataSource(User $admin, Site $site): DataSource
    {
        return DataSource::updateOrCreate(
            ['tenant_id' => $admin->id, 'site_id' => $site->id, 'name' => 'US Cities Plumber Data'],
            [
                'type' => DataSourceType::Csv,
                'config' => [
                    'file_path' => 'data-sources/plumbers-cities.csv',
                    'delimiter' => ',',
                    'has_header' => true,
                    'column_mapping' => [
                        'city_name' => 'City',
                        'state' => 'State',
                        'service_name' => 'Service',
                        'population' => 'Population',
                        'description' => 'Description',
                        'phone' => 'Phone',
                        'rating' => 'Rating',
                    ],
                ],
                'last_synced_at' => now(),
            ]
        );
    }

    private function createComparisonDataSource(User $admin, Site $site): DataSource
    {
        return DataSource::updateOrCreate(
            ['tenant_id' => $admin->id, 'site_id' => $site->id, 'name' => 'Tech Products Comparison Data'],
            [
                'type' => DataSourceType::Csv,
                'config' => [
                    'file_path' => 'data-sources/tech-comparisons.csv',
                    'delimiter' => ',',
                    'has_header' => true,
                    'column_mapping' => [
                        'product_a' => 'Product A',
                        'product_b' => 'Product B',
                        'category' => 'Category',
                        'product_a_price' => 'Price A',
                        'product_b_price' => 'Price B',
                        'product_a_rating' => 'Rating A',
                        'product_b_rating' => 'Rating B',
                        'winner' => 'Winner',
                        'summary' => 'Summary',
                    ],
                ],
                'last_synced_at' => now(),
            ]
        );
    }

    private function createCityDataEntries(User $admin, DataSource $dataSource): void
    {
        $cities = [
            [
                'city_name' => 'Austin',
                'state' => 'Texas',
                'service_name' => 'Plumbing Services',
                'population' => '978,908',
                'description' => 'Austin is the capital of Texas, known for its live music scene, tech industry, and vibrant culture. The city has a growing need for reliable plumbing services.',
                'phone' => '(512) 555-0147',
                'rating' => 4.7,
            ],
            [
                'city_name' => 'Denver',
                'state' => 'Colorado',
                'service_name' => 'Plumbing Services',
                'population' => '715,522',
                'description' => 'Denver, the Mile High City, is known for its outdoor activities and booming housing market. Quality plumbing services are essential for both new and older homes.',
                'phone' => '(303) 555-0289',
                'rating' => 4.5,
            ],
            [
                'city_name' => 'Seattle',
                'state' => 'Washington',
                'service_name' => 'Plumbing Services',
                'population' => '737,015',
                'description' => 'Seattle is a major tech hub with older neighborhoods that frequently require professional plumbing maintenance and repair services.',
                'phone' => '(206) 555-0193',
                'rating' => 4.8,
            ],
            [
                'city_name' => 'Nashville',
                'state' => 'Tennessee',
                'service_name' => 'Plumbing Services',
                'population' => '689,447',
                'description' => 'Nashville is one of the fastest-growing cities in America. Rapid construction means high demand for skilled plumbers and plumbing contractors.',
                'phone' => '(615) 555-0321',
                'rating' => 4.6,
            ],
            [
                'city_name' => 'Portland',
                'state' => 'Oregon',
                'service_name' => 'Plumbing Services',
                'population' => '652,503',
                'description' => 'Portland is known for its eco-conscious residents who prefer green plumbing solutions and water-efficient fixtures.',
                'phone' => '(503) 555-0412',
                'rating' => 4.4,
            ],
        ];

        foreach ($cities as $cityData) {
            $checksum = md5(json_encode($cityData));

            DataEntry::updateOrCreate(
                ['data_source_id' => $dataSource->id, 'checksum' => $checksum],
                [
                    'tenant_id' => $admin->id,
                    'data' => $cityData,
                ]
            );
        }
    }

    private function createComparisonDataEntries(User $admin, DataSource $dataSource): void
    {
        $comparisons = [
            [
                'product_a' => 'MacBook Pro M3',
                'product_b' => 'Dell XPS 15',
                'category' => 'Laptops',
                'product_a_price' => '$1,999',
                'product_b_price' => '$1,599',
                'product_a_rating' => 4.8,
                'product_b_rating' => 4.5,
                'winner' => 'MacBook Pro M3',
                'summary' => 'The MacBook Pro M3 wins for its superior performance, battery life, and display quality, though the Dell XPS 15 offers better value at a lower price point.',
            ],
            [
                'product_a' => 'iPhone 16 Pro',
                'product_b' => 'Samsung Galaxy S25 Ultra',
                'category' => 'Smartphones',
                'product_a_price' => '$1,099',
                'product_b_price' => '$1,299',
                'product_a_rating' => 4.7,
                'product_b_rating' => 4.6,
                'winner' => 'iPhone 16 Pro',
                'summary' => 'Both flagships are excellent, but the iPhone 16 Pro edges ahead with tighter ecosystem integration and longer software support, while the Galaxy S25 Ultra excels in customization and S Pen features.',
            ],
            [
                'product_a' => 'Sony WH-1000XM5',
                'product_b' => 'Bose QuietComfort Ultra',
                'category' => 'Headphones',
                'product_a_price' => '$349',
                'product_b_price' => '$429',
                'product_a_rating' => 4.6,
                'product_b_rating' => 4.7,
                'winner' => 'Bose QuietComfort Ultra',
                'summary' => 'The Bose QC Ultra takes the crown for best noise cancellation and comfort, while the Sony XM5 offers better value and equally impressive sound quality.',
            ],
            [
                'product_a' => 'ChatGPT Plus',
                'product_b' => 'Claude Pro',
                'category' => 'AI Assistants',
                'product_a_price' => '$20/mo',
                'product_b_price' => '$20/mo',
                'product_a_rating' => 4.5,
                'product_b_rating' => 4.6,
                'winner' => 'Tie',
                'summary' => 'Both AI assistants offer impressive capabilities. ChatGPT Plus excels at creative tasks and plugins, while Claude Pro stands out for accuracy, nuanced reasoning, and longer context handling.',
            ],
            [
                'product_a' => 'Notion',
                'product_b' => 'Obsidian',
                'category' => 'Note-Taking Apps',
                'product_a_price' => '$10/mo',
                'product_b_price' => 'Free (Sync $4/mo)',
                'product_a_rating' => 4.6,
                'product_b_rating' => 4.7,
                'winner' => 'Obsidian',
                'summary' => 'Obsidian wins for power users who value local-first data ownership and extensive plugin ecosystem. Notion is better for teams needing databases, wikis, and real-time collaboration out of the box.',
            ],
        ];

        foreach ($comparisons as $comparisonData) {
            $checksum = md5(json_encode($comparisonData));

            DataEntry::updateOrCreate(
                ['data_source_id' => $dataSource->id, 'checksum' => $checksum],
                [
                    'tenant_id' => $admin->id,
                    'data' => $comparisonData,
                ]
            );
        }
    }

    /**
     * @return Page[]
     */
    private function createCityPages(User $admin, Site $site, ?PageTemplate $template): array
    {
        $pages = [];

        $cityPages = [
            [
                'title' => 'Best Plumbing Services in Austin, Texas',
                'slug' => 'best-plumbing-services-austin-texas',
                'status' => ContentStatus::Published,
                'generation_method' => GenerationMethod::Template,
                'variable_data' => [
                    'city_name' => 'Austin',
                    'state' => 'Texas',
                    'service_name' => 'Plumbing Services',
                    'population' => '978,908',
                    'description' => 'Austin is the capital of Texas, known for its live music scene, tech industry, and vibrant culture.',
                    'phone' => '(512) 555-0147',
                    'rating' => 4.7,
                ],
                'meta_title' => 'Best Plumbers in Austin, TX | Top Rated 2026',
                'meta_description' => 'Find the best plumbers in Austin, Texas. Compare ratings, prices, and reviews for top-rated plumbing services near you.',
                'priority' => 0.8,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Best Plumbing Services in Denver, Colorado',
                'slug' => 'best-plumbing-services-denver-colorado',
                'status' => ContentStatus::Published,
                'generation_method' => GenerationMethod::Template,
                'variable_data' => [
                    'city_name' => 'Denver',
                    'state' => 'Colorado',
                    'service_name' => 'Plumbing Services',
                    'population' => '715,522',
                    'description' => 'Denver, the Mile High City, is known for its outdoor activities and booming housing market.',
                    'phone' => '(303) 555-0289',
                    'rating' => 4.5,
                ],
                'meta_title' => 'Best Plumbers in Denver, CO | Top Rated 2026',
                'meta_description' => 'Find the best plumbers in Denver, Colorado. Compare ratings, prices, and reviews for top-rated plumbing services.',
                'priority' => 0.8,
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'Best Plumbing Services in Seattle, Washington',
                'slug' => 'best-plumbing-services-seattle-washington',
                'status' => ContentStatus::Draft,
                'generation_method' => GenerationMethod::Hybrid,
                'variable_data' => [
                    'city_name' => 'Seattle',
                    'state' => 'Washington',
                    'service_name' => 'Plumbing Services',
                    'population' => '737,015',
                    'description' => 'Seattle is a major tech hub with older neighborhoods that frequently require professional plumbing maintenance.',
                    'phone' => '(206) 555-0193',
                    'rating' => 4.8,
                ],
                'meta_title' => 'Best Plumbers in Seattle, WA | Top Rated 2026',
                'meta_description' => 'Find the best plumbers in Seattle, Washington. Compare ratings, prices, and reviews for top-rated plumbing services.',
                'priority' => 0.7,
                'published_at' => null,
            ],
        ];

        foreach ($cityPages as $pageData) {
            $page = Page::updateOrCreate(
                ['site_id' => $site->id, 'slug' => $pageData['slug']],
                array_merge($pageData, [
                    'tenant_id' => $admin->id,
                    'template_id' => $template?->id,
                    'content_html' => $this->renderCityHtml($pageData['variable_data']),
                    'schema_markup' => [
                        '@context' => 'https://schema.org',
                        '@type' => 'LocalBusiness',
                        'name' => $pageData['variable_data']['service_name'] . ' in ' . $pageData['variable_data']['city_name'],
                        'address' => [
                            '@type' => 'PostalAddress',
                            'addressLocality' => $pageData['variable_data']['city_name'],
                            'addressRegion' => $pageData['variable_data']['state'],
                        ],
                        'telephone' => $pageData['variable_data']['phone'],
                        'aggregateRating' => [
                            '@type' => 'AggregateRating',
                            'ratingValue' => $pageData['variable_data']['rating'],
                            'bestRating' => 5,
                        ],
                    ],
                ])
            );

            $pages[] = $page;
        }

        return $pages;
    }

    /**
     * @return Page[]
     */
    private function createComparisonPages(User $admin, Site $site, ?PageTemplate $template): array
    {
        $pages = [];

        $comparisonPages = [
            [
                'title' => 'MacBook Pro M3 vs Dell XPS 15 - Laptop Comparison',
                'slug' => 'macbook-pro-m3-vs-dell-xps-15',
                'status' => ContentStatus::Published,
                'generation_method' => GenerationMethod::Template,
                'variable_data' => [
                    'product_a' => 'MacBook Pro M3',
                    'product_b' => 'Dell XPS 15',
                    'category' => 'Laptops',
                    'product_a_price' => '$1,999',
                    'product_b_price' => '$1,599',
                    'product_a_rating' => 4.8,
                    'product_b_rating' => 4.5,
                    'winner' => 'MacBook Pro M3',
                    'summary' => 'The MacBook Pro M3 wins for its superior performance, battery life, and display quality, though the Dell XPS 15 offers better value.',
                ],
                'meta_title' => 'MacBook Pro M3 vs Dell XPS 15 (2026) - Which Laptop Wins?',
                'meta_description' => 'Detailed comparison of MacBook Pro M3 and Dell XPS 15. Compare specs, pricing, performance, and user ratings side by side.',
                'priority' => 0.9,
                'published_at' => now()->subDays(14),
            ],
            [
                'title' => 'iPhone 16 Pro vs Samsung Galaxy S25 Ultra - Smartphone Showdown',
                'slug' => 'iphone-16-pro-vs-samsung-galaxy-s25-ultra',
                'status' => ContentStatus::Published,
                'generation_method' => GenerationMethod::Hybrid,
                'variable_data' => [
                    'product_a' => 'iPhone 16 Pro',
                    'product_b' => 'Samsung Galaxy S25 Ultra',
                    'category' => 'Smartphones',
                    'product_a_price' => '$1,099',
                    'product_b_price' => '$1,299',
                    'product_a_rating' => 4.7,
                    'product_b_rating' => 4.6,
                    'winner' => 'iPhone 16 Pro',
                    'summary' => 'The iPhone 16 Pro edges ahead with ecosystem integration and software support, while the Galaxy S25 Ultra excels in customization.',
                ],
                'meta_title' => 'iPhone 16 Pro vs Galaxy S25 Ultra (2026) - Full Comparison',
                'meta_description' => 'iPhone 16 Pro vs Samsung Galaxy S25 Ultra comparison. See which flagship smartphone wins on camera, performance, battery, and price.',
                'priority' => 0.9,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Sony WH-1000XM5 vs Bose QuietComfort Ultra - Headphones Battle',
                'slug' => 'sony-wh-1000xm5-vs-bose-quietcomfort-ultra',
                'status' => ContentStatus::Draft,
                'generation_method' => GenerationMethod::AI,
                'variable_data' => [
                    'product_a' => 'Sony WH-1000XM5',
                    'product_b' => 'Bose QuietComfort Ultra',
                    'category' => 'Headphones',
                    'product_a_price' => '$349',
                    'product_b_price' => '$429',
                    'product_a_rating' => 4.6,
                    'product_b_rating' => 4.7,
                    'winner' => 'Bose QuietComfort Ultra',
                    'summary' => 'The Bose QC Ultra wins for noise cancellation and comfort, while the Sony XM5 offers better value.',
                ],
                'meta_title' => 'Sony XM5 vs Bose QC Ultra (2026) - Best ANC Headphones?',
                'meta_description' => 'Sony WH-1000XM5 vs Bose QuietComfort Ultra. Our detailed headphones comparison covers sound quality, ANC, comfort, and battery life.',
                'priority' => 0.7,
                'published_at' => null,
            ],
        ];

        foreach ($comparisonPages as $pageData) {
            $page = Page::updateOrCreate(
                ['site_id' => $site->id, 'slug' => $pageData['slug']],
                array_merge($pageData, [
                    'tenant_id' => $admin->id,
                    'template_id' => $template?->id,
                    'content_html' => $this->renderComparisonHtml($pageData['variable_data']),
                    'schema_markup' => [
                        '@context' => 'https://schema.org',
                        '@type' => 'Article',
                        'headline' => $pageData['title'],
                        'description' => $pageData['meta_description'],
                        'datePublished' => $pageData['published_at']?->toIso8601String(),
                        'author' => [
                            '@type' => 'Organization',
                            'name' => 'Tech Compare Hub',
                        ],
                    ],
                ])
            );

            $pages[] = $page;
        }

        return $pages;
    }

    /**
     * @param Page[] $pages
     */
    private function createInternalLinks(Site $site, array $pages): void
    {
        if (count($pages) < 2) {
            return;
        }

        // Link first page to second page
        InternalLink::updateOrCreate(
            [
                'site_id' => $site->id,
                'source_page_id' => $pages[0]->id,
                'target_page_id' => $pages[1]->id,
            ],
            [
                'anchor_text' => 'See also: ' . $pages[1]->title,
                'link_type' => 'related',
            ]
        );

        // Link second page to first page
        InternalLink::updateOrCreate(
            [
                'site_id' => $site->id,
                'source_page_id' => $pages[1]->id,
                'target_page_id' => $pages[0]->id,
            ],
            [
                'anchor_text' => 'Related: ' . $pages[0]->title,
                'link_type' => 'related',
            ]
        );

        // Link first page to third page if it exists
        if (count($pages) >= 3) {
            InternalLink::updateOrCreate(
                [
                    'site_id' => $site->id,
                    'source_page_id' => $pages[0]->id,
                    'target_page_id' => $pages[2]->id,
                ],
                [
                    'anchor_text' => 'You might also like: ' . $pages[2]->title,
                    'link_type' => 'suggested',
                ]
            );
        }
    }

    private function createAdPlacements(User $admin, Site $site): void
    {
        AdPlacement::updateOrCreate(
            ['tenant_id' => $admin->id, 'site_id' => $site->id, 'name' => 'Top Banner Ad'],
            [
                'type' => 'adsense',
                'code' => '<ins class="adsbygoogle" style="display:block" data-ad-client="' . ($site->adsense_publisher_id ?? 'ca-pub-0000000000000000') . '" data-ad-slot="1234567890" data-ad-format="auto" data-full-width-responsive="true"></ins>',
                'position' => 'above_content',
                'is_active' => true,
            ]
        );

        AdPlacement::updateOrCreate(
            ['tenant_id' => $admin->id, 'site_id' => $site->id, 'name' => 'In-Content Ad'],
            [
                'type' => 'adsense',
                'code' => '<ins class="adsbygoogle" style="display:block; text-align:center;" data-ad-layout="in-article" data-ad-client="' . ($site->adsense_publisher_id ?? 'ca-pub-0000000000000000') . '" data-ad-slot="0987654321" data-ad-format="fluid"></ins>',
                'position' => 'in_content',
                'is_active' => true,
            ]
        );

        AdPlacement::updateOrCreate(
            ['tenant_id' => $admin->id, 'site_id' => $site->id, 'name' => 'Bottom Sidebar Ad'],
            [
                'type' => 'custom',
                'code' => '<div class="custom-ad-placeholder" style="background:#f0f0f0;padding:20px;text-align:center;border:1px dashed #ccc;border-radius:8px;"><p>Your Ad Here</p><p style="font-size:0.8rem;color:#999;">300x250 Display Ad</p></div>',
                'position' => 'sidebar_bottom',
                'is_active' => false,
            ]
        );
    }

    private function createAffiliateLinks(User $admin, Site $site): void
    {
        $affiliateData = $site->niche_type === NicheType::City
            ? [
                [
                    'original_url' => 'https://www.homeadvisor.com/plumbing',
                    'affiliate_url' => 'https://www.homeadvisor.com/plumbing?ref=bestplumbersusa&utm_source=partner',
                    'keyword' => 'find a plumber',
                    'clicks' => 142,
                ],
                [
                    'original_url' => 'https://www.angieslist.com/plumbing',
                    'affiliate_url' => 'https://www.angieslist.com/plumbing?aff=bestplumbers2026',
                    'keyword' => 'plumber reviews',
                    'clicks' => 89,
                ],
            ]
            : [
                [
                    'original_url' => 'https://www.amazon.com/macbook-pro',
                    'affiliate_url' => 'https://www.amazon.com/macbook-pro?tag=techcompare-20',
                    'keyword' => 'buy MacBook Pro',
                    'clicks' => 312,
                ],
                [
                    'original_url' => 'https://www.bestbuy.com/dell-xps',
                    'affiliate_url' => 'https://www.bestbuy.com/dell-xps?ref=techcompare&irclickid=abc123',
                    'keyword' => 'buy Dell XPS',
                    'clicks' => 198,
                ],
            ];

        foreach ($affiliateData as $link) {
            AffiliateLink::updateOrCreate(
                [
                    'tenant_id' => $admin->id,
                    'site_id' => $site->id,
                    'keyword' => $link['keyword'],
                ],
                [
                    'original_url' => $link['original_url'],
                    'affiliate_url' => $link['affiliate_url'],
                    'clicks' => $link['clicks'],
                ]
            );
        }
    }

    private function renderCityHtml(array $data): string
    {
        $html = '<div class="city-page">
        <h1>Best ' . e($data['service_name']) . ' in ' . e($data['city_name']) . ', ' . e($data['state']) . '</h1>
        <div class="hero-section">
            <p>Looking for top-rated ' . e($data['service_name']) . ' in ' . e($data['city_name']) . '? We\'ve compiled the best options for residents of ' . e($data['city_name']) . ', ' . e($data['state']) . '.</p>
        </div>
        <div class="city-info">
            <h2>About ' . e($data['city_name']) . '</h2>
            <p>' . e($data['city_name']) . ' is a vibrant city in ' . e($data['state']) . ' with a population of ' . e($data['population']) . '. ' . e($data['description'] ?? '') . '</p>
        </div>
        <div class="cta-section">
            <h2>Contact the Best ' . e($data['service_name']) . '</h2>
            <p>Call now: ' . e($data['phone'] ?? '') . '</p>
            <p>Average Rating: ' . e($data['rating'] ?? 'N/A') . '/5</p>
        </div>
    </div>';

        return $html;
    }

    private function renderComparisonHtml(array $data): string
    {
        $html = '<div class="comparison-page">
        <h1>' . e($data['product_a']) . ' vs ' . e($data['product_b']) . ' - ' . e($data['category']) . ' Comparison</h1>
        <div class="comparison-intro">
            <p>Trying to decide between ' . e($data['product_a']) . ' and ' . e($data['product_b']) . '? Our detailed ' . e($data['category']) . ' comparison breaks down the key differences.</p>
        </div>
        <div class="comparison-table">
            <table>
                <thead>
                    <tr>
                        <th>Feature</th>
                        <th>' . e($data['product_a']) . '</th>
                        <th>' . e($data['product_b']) . '</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Price</td>
                        <td>' . e($data['product_a_price'] ?? 'N/A') . '</td>
                        <td>' . e($data['product_b_price'] ?? 'N/A') . '</td>
                    </tr>
                    <tr>
                        <td>Rating</td>
                        <td>' . e($data['product_a_rating'] ?? 'N/A') . '/5</td>
                        <td>' . e($data['product_b_rating'] ?? 'N/A') . '/5</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="verdict-section">
            <h2>Our Verdict</h2>
            <p class="winner-badge">Winner: ' . e($data['winner'] ?? 'TBD') . '</p>
            <p>' . e($data['summary'] ?? '') . '</p>
        </div>
    </div>';

        return $html;
    }
}
