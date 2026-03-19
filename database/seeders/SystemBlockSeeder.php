<?php

namespace Database\Seeders;

use App\Models\BuilderBlock;
use Illuminate\Database\Seeder;

class SystemBlockSeeder extends Seeder
{
    public function run(): void
    {
        $this->createHeroBanner();
        $this->createComparisonTable();
        $this->createListingGrid();
        $this->createCityInfoCard();
        $this->createFaqAccordion();
        $this->createCtaSection();
        $this->createAdSlot();
        $this->createTestimonialSlider();
    }

    private function createHeroBanner(): void
    {
        BuilderBlock::updateOrCreate(
            ['name' => 'Hero Banner', 'is_system' => true],
            [
                'tenant_id' => null,
                'category' => 'layout',
                'component_json' => [
                    'type' => 'hero-banner',
                    'tagName' => 'section',
                    'classes' => ['hero-banner'],
                    'style' => [
                        'background' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                        'color' => '#ffffff',
                        'padding' => '80px 40px',
                        'text-align' => 'center',
                        'border-radius' => '12px',
                        'margin-bottom' => '24px',
                    ],
                    'components' => [
                        [
                            'type' => 'text',
                            'tagName' => 'h1',
                            'classes' => ['hero-title'],
                            'content' => '{{title}}',
                            'style' => [
                                'font-size' => '3rem',
                                'font-weight' => '800',
                                'margin-bottom' => '16px',
                                'line-height' => '1.2',
                            ],
                        ],
                        [
                            'type' => 'text',
                            'tagName' => 'p',
                            'classes' => ['hero-subtitle'],
                            'content' => '{{subtitle}}',
                            'style' => [
                                'font-size' => '1.25rem',
                                'opacity' => '0.9',
                                'max-width' => '600px',
                                'margin' => '0 auto 32px auto',
                            ],
                        ],
                        [
                            'type' => 'link',
                            'tagName' => 'a',
                            'classes' => ['hero-cta-btn'],
                            'attributes' => ['href' => '{{cta_url}}'],
                            'content' => '{{cta_text}}',
                            'style' => [
                                'display' => 'inline-block',
                                'background' => '#ffffff',
                                'color' => '#667eea',
                                'padding' => '14px 36px',
                                'border-radius' => '8px',
                                'font-weight' => '700',
                                'font-size' => '1.1rem',
                                'text-decoration' => 'none',
                                'transition' => 'transform 0.2s, box-shadow 0.2s',
                            ],
                        ],
                    ],
                ],
                'default_data' => [
                    'title' => 'Your Main Headline Here',
                    'subtitle' => 'A compelling subtitle that explains your value proposition to visitors.',
                    'cta_text' => 'Get Started Now',
                    'cta_url' => '#',
                ],
            ]
        );
    }

    private function createComparisonTable(): void
    {
        BuilderBlock::updateOrCreate(
            ['name' => 'Comparison Table', 'is_system' => true],
            [
                'tenant_id' => null,
                'category' => 'content',
                'component_json' => [
                    'type' => 'comparison-table',
                    'tagName' => 'div',
                    'classes' => ['comparison-table-block'],
                    'style' => [
                        'padding' => '24px',
                        'margin-bottom' => '24px',
                    ],
                    'components' => [
                        [
                            'type' => 'text',
                            'tagName' => 'h2',
                            'content' => '{{table_title}}',
                            'style' => [
                                'text-align' => 'center',
                                'margin-bottom' => '24px',
                                'font-size' => '1.75rem',
                                'color' => '#1a202c',
                            ],
                        ],
                        [
                            'type' => 'table',
                            'tagName' => 'table',
                            'classes' => ['comp-table'],
                            'style' => [
                                'width' => '100%',
                                'border-collapse' => 'collapse',
                                'border-radius' => '8px',
                                'overflow' => 'hidden',
                                'box-shadow' => '0 1px 3px rgba(0,0,0,0.1)',
                            ],
                            'components' => [
                                [
                                    'tagName' => 'thead',
                                    'style' => ['background' => '#2d3748', 'color' => '#ffffff'],
                                    'components' => [
                                        [
                                            'tagName' => 'tr',
                                            'components' => [
                                                ['tagName' => 'th', 'content' => 'Feature', 'style' => ['padding' => '14px 18px', 'text-align' => 'left']],
                                                ['tagName' => 'th', 'content' => '{{column_a}}', 'style' => ['padding' => '14px 18px', 'text-align' => 'center']],
                                                ['tagName' => 'th', 'content' => '{{column_b}}', 'style' => ['padding' => '14px 18px', 'text-align' => 'center']],
                                            ],
                                        ],
                                    ],
                                ],
                                [
                                    'tagName' => 'tbody',
                                    'components' => [
                                        [
                                            'tagName' => 'tr',
                                            'style' => ['border-bottom' => '1px solid #e2e8f0'],
                                            'components' => [
                                                ['tagName' => 'td', 'content' => '{{feature_1}}', 'style' => ['padding' => '12px 18px', 'font-weight' => '600']],
                                                ['tagName' => 'td', 'content' => '{{value_a_1}}', 'style' => ['padding' => '12px 18px', 'text-align' => 'center']],
                                                ['tagName' => 'td', 'content' => '{{value_b_1}}', 'style' => ['padding' => '12px 18px', 'text-align' => 'center']],
                                            ],
                                        ],
                                        [
                                            'tagName' => 'tr',
                                            'style' => ['border-bottom' => '1px solid #e2e8f0', 'background' => '#f7fafc'],
                                            'components' => [
                                                ['tagName' => 'td', 'content' => '{{feature_2}}', 'style' => ['padding' => '12px 18px', 'font-weight' => '600']],
                                                ['tagName' => 'td', 'content' => '{{value_a_2}}', 'style' => ['padding' => '12px 18px', 'text-align' => 'center']],
                                                ['tagName' => 'td', 'content' => '{{value_b_2}}', 'style' => ['padding' => '12px 18px', 'text-align' => 'center']],
                                            ],
                                        ],
                                        [
                                            'tagName' => 'tr',
                                            'style' => ['border-bottom' => '1px solid #e2e8f0'],
                                            'components' => [
                                                ['tagName' => 'td', 'content' => '{{feature_3}}', 'style' => ['padding' => '12px 18px', 'font-weight' => '600']],
                                                ['tagName' => 'td', 'content' => '{{value_a_3}}', 'style' => ['padding' => '12px 18px', 'text-align' => 'center']],
                                                ['tagName' => 'td', 'content' => '{{value_b_3}}', 'style' => ['padding' => '12px 18px', 'text-align' => 'center']],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'default_data' => [
                    'table_title' => 'Feature Comparison',
                    'column_a' => 'Product A',
                    'column_b' => 'Product B',
                    'feature_1' => 'Price',
                    'value_a_1' => '$29/mo',
                    'value_b_1' => '$39/mo',
                    'feature_2' => 'Storage',
                    'value_a_2' => '10 GB',
                    'value_b_2' => '25 GB',
                    'feature_3' => 'Support',
                    'value_a_3' => 'Email',
                    'value_b_3' => '24/7 Live',
                ],
            ]
        );
    }

    private function createListingGrid(): void
    {
        BuilderBlock::updateOrCreate(
            ['name' => 'Listing Grid', 'is_system' => true],
            [
                'tenant_id' => null,
                'category' => 'content',
                'component_json' => [
                    'type' => 'listing-grid',
                    'tagName' => 'div',
                    'classes' => ['listing-grid-block'],
                    'style' => [
                        'display' => 'grid',
                        'grid-template-columns' => 'repeat(3, 1fr)',
                        'gap' => '24px',
                        'padding' => '24px 0',
                        'margin-bottom' => '24px',
                    ],
                    'components' => [
                        [
                            'tagName' => 'div',
                            'classes' => ['listing-card'],
                            'style' => [
                                'background' => '#ffffff',
                                'border' => '1px solid #e2e8f0',
                                'border-radius' => '12px',
                                'padding' => '24px',
                                'transition' => 'box-shadow 0.2s',
                            ],
                            'components' => [
                                ['tagName' => 'h3', 'content' => '{{item_1_name}}', 'style' => ['margin-top' => '0', 'color' => '#1a202c', 'font-size' => '1.15rem']],
                                ['tagName' => 'p', 'classes' => ['listing-category'], 'content' => '{{item_1_category}}', 'style' => ['color' => '#667eea', 'font-size' => '0.85rem', 'font-weight' => '600']],
                                ['tagName' => 'p', 'content' => '{{item_1_description}}', 'style' => ['color' => '#718096', 'font-size' => '0.95rem', 'line-height' => '1.6']],
                                ['tagName' => 'span', 'content' => '{{item_1_rating}}/5', 'style' => ['color' => '#ecc94b', 'font-weight' => 'bold']],
                            ],
                        ],
                        [
                            'tagName' => 'div',
                            'classes' => ['listing-card'],
                            'style' => [
                                'background' => '#ffffff',
                                'border' => '1px solid #e2e8f0',
                                'border-radius' => '12px',
                                'padding' => '24px',
                                'transition' => 'box-shadow 0.2s',
                            ],
                            'components' => [
                                ['tagName' => 'h3', 'content' => '{{item_2_name}}', 'style' => ['margin-top' => '0', 'color' => '#1a202c', 'font-size' => '1.15rem']],
                                ['tagName' => 'p', 'classes' => ['listing-category'], 'content' => '{{item_2_category}}', 'style' => ['color' => '#667eea', 'font-size' => '0.85rem', 'font-weight' => '600']],
                                ['tagName' => 'p', 'content' => '{{item_2_description}}', 'style' => ['color' => '#718096', 'font-size' => '0.95rem', 'line-height' => '1.6']],
                                ['tagName' => 'span', 'content' => '{{item_2_rating}}/5', 'style' => ['color' => '#ecc94b', 'font-weight' => 'bold']],
                            ],
                        ],
                        [
                            'tagName' => 'div',
                            'classes' => ['listing-card'],
                            'style' => [
                                'background' => '#ffffff',
                                'border' => '1px solid #e2e8f0',
                                'border-radius' => '12px',
                                'padding' => '24px',
                                'transition' => 'box-shadow 0.2s',
                            ],
                            'components' => [
                                ['tagName' => 'h3', 'content' => '{{item_3_name}}', 'style' => ['margin-top' => '0', 'color' => '#1a202c', 'font-size' => '1.15rem']],
                                ['tagName' => 'p', 'classes' => ['listing-category'], 'content' => '{{item_3_category}}', 'style' => ['color' => '#667eea', 'font-size' => '0.85rem', 'font-weight' => '600']],
                                ['tagName' => 'p', 'content' => '{{item_3_description}}', 'style' => ['color' => '#718096', 'font-size' => '0.95rem', 'line-height' => '1.6']],
                                ['tagName' => 'span', 'content' => '{{item_3_rating}}/5', 'style' => ['color' => '#ecc94b', 'font-weight' => 'bold']],
                            ],
                        ],
                    ],
                ],
                'default_data' => [
                    'item_1_name' => 'Business Name One',
                    'item_1_category' => 'Restaurant',
                    'item_1_description' => 'A fantastic local business serving the community.',
                    'item_1_rating' => '4.8',
                    'item_2_name' => 'Business Name Two',
                    'item_2_category' => 'Retail',
                    'item_2_description' => 'Quality products and exceptional customer service.',
                    'item_2_rating' => '4.5',
                    'item_3_name' => 'Business Name Three',
                    'item_3_category' => 'Services',
                    'item_3_description' => 'Professional services you can trust and rely on.',
                    'item_3_rating' => '4.7',
                ],
            ]
        );
    }

    private function createCityInfoCard(): void
    {
        BuilderBlock::updateOrCreate(
            ['name' => 'City Info Card', 'is_system' => true],
            [
                'tenant_id' => null,
                'category' => 'content',
                'component_json' => [
                    'type' => 'city-info-card',
                    'tagName' => 'div',
                    'classes' => ['city-info-card'],
                    'style' => [
                        'background' => '#ffffff',
                        'border' => '1px solid #e2e8f0',
                        'border-radius' => '12px',
                        'padding' => '32px',
                        'margin-bottom' => '24px',
                        'box-shadow' => '0 1px 3px rgba(0,0,0,0.08)',
                    ],
                    'components' => [
                        [
                            'tagName' => 'h2',
                            'content' => '{{city_name}}, {{state}}',
                            'style' => [
                                'margin-top' => '0',
                                'font-size' => '1.5rem',
                                'color' => '#1a202c',
                                'margin-bottom' => '20px',
                                'padding-bottom' => '12px',
                                'border-bottom' => '2px solid #667eea',
                            ],
                        ],
                        [
                            'tagName' => 'div',
                            'classes' => ['stats-grid'],
                            'style' => [
                                'display' => 'grid',
                                'grid-template-columns' => 'repeat(3, 1fr)',
                                'gap' => '20px',
                                'margin-bottom' => '20px',
                            ],
                            'components' => [
                                [
                                    'tagName' => 'div',
                                    'classes' => ['stat-item'],
                                    'style' => ['text-align' => 'center', 'padding' => '16px', 'background' => '#f7fafc', 'border-radius' => '8px'],
                                    'components' => [
                                        ['tagName' => 'div', 'content' => '{{population}}', 'style' => ['font-size' => '1.5rem', 'font-weight' => '800', 'color' => '#667eea']],
                                        ['tagName' => 'div', 'content' => 'Population', 'style' => ['font-size' => '0.85rem', 'color' => '#a0aec0', 'margin-top' => '4px']],
                                    ],
                                ],
                                [
                                    'tagName' => 'div',
                                    'classes' => ['stat-item'],
                                    'style' => ['text-align' => 'center', 'padding' => '16px', 'background' => '#f7fafc', 'border-radius' => '8px'],
                                    'components' => [
                                        ['tagName' => 'div', 'content' => '{{avg_income}}', 'style' => ['font-size' => '1.5rem', 'font-weight' => '800', 'color' => '#48bb78']],
                                        ['tagName' => 'div', 'content' => 'Avg Income', 'style' => ['font-size' => '0.85rem', 'color' => '#a0aec0', 'margin-top' => '4px']],
                                    ],
                                ],
                                [
                                    'tagName' => 'div',
                                    'classes' => ['stat-item'],
                                    'style' => ['text-align' => 'center', 'padding' => '16px', 'background' => '#f7fafc', 'border-radius' => '8px'],
                                    'components' => [
                                        ['tagName' => 'div', 'content' => '{{businesses_count}}', 'style' => ['font-size' => '1.5rem', 'font-weight' => '800', 'color' => '#ed8936']],
                                        ['tagName' => 'div', 'content' => 'Businesses', 'style' => ['font-size' => '0.85rem', 'color' => '#a0aec0', 'margin-top' => '4px']],
                                    ],
                                ],
                            ],
                        ],
                        [
                            'tagName' => 'p',
                            'content' => '{{city_description}}',
                            'style' => ['color' => '#4a5568', 'line-height' => '1.7', 'margin' => '0'],
                        ],
                    ],
                ],
                'default_data' => [
                    'city_name' => 'San Francisco',
                    'state' => 'CA',
                    'population' => '873,965',
                    'avg_income' => '$112,449',
                    'businesses_count' => '12,500+',
                    'city_description' => 'San Francisco is a cultural, commercial, and financial center in Northern California, known for its iconic landmarks and vibrant tech industry.',
                ],
            ]
        );
    }

    private function createFaqAccordion(): void
    {
        BuilderBlock::updateOrCreate(
            ['name' => 'FAQ Accordion', 'is_system' => true],
            [
                'tenant_id' => null,
                'category' => 'content',
                'component_json' => [
                    'type' => 'faq-accordion',
                    'tagName' => 'div',
                    'classes' => ['faq-section'],
                    'style' => [
                        'max-width' => '800px',
                        'margin' => '0 auto 24px auto',
                        'padding' => '24px',
                    ],
                    'components' => [
                        [
                            'tagName' => 'h2',
                            'content' => 'Frequently Asked Questions',
                            'style' => ['text-align' => 'center', 'margin-bottom' => '32px', 'color' => '#1a202c', 'font-size' => '1.75rem'],
                        ],
                        [
                            'tagName' => 'details',
                            'classes' => ['faq-item'],
                            'style' => [
                                'background' => '#ffffff',
                                'border' => '1px solid #e2e8f0',
                                'border-radius' => '8px',
                                'margin-bottom' => '12px',
                                'overflow' => 'hidden',
                            ],
                            'components' => [
                                ['tagName' => 'summary', 'content' => '{{question_1}}', 'style' => ['padding' => '16px 20px', 'cursor' => 'pointer', 'font-weight' => '600', 'color' => '#2d3748', 'list-style' => 'none']],
                                ['tagName' => 'p', 'content' => '{{answer_1}}', 'style' => ['padding' => '0 20px 16px', 'color' => '#718096', 'line-height' => '1.6', 'margin' => '0']],
                            ],
                        ],
                        [
                            'tagName' => 'details',
                            'classes' => ['faq-item'],
                            'style' => [
                                'background' => '#ffffff',
                                'border' => '1px solid #e2e8f0',
                                'border-radius' => '8px',
                                'margin-bottom' => '12px',
                                'overflow' => 'hidden',
                            ],
                            'components' => [
                                ['tagName' => 'summary', 'content' => '{{question_2}}', 'style' => ['padding' => '16px 20px', 'cursor' => 'pointer', 'font-weight' => '600', 'color' => '#2d3748', 'list-style' => 'none']],
                                ['tagName' => 'p', 'content' => '{{answer_2}}', 'style' => ['padding' => '0 20px 16px', 'color' => '#718096', 'line-height' => '1.6', 'margin' => '0']],
                            ],
                        ],
                        [
                            'tagName' => 'details',
                            'classes' => ['faq-item'],
                            'style' => [
                                'background' => '#ffffff',
                                'border' => '1px solid #e2e8f0',
                                'border-radius' => '8px',
                                'margin-bottom' => '12px',
                                'overflow' => 'hidden',
                            ],
                            'components' => [
                                ['tagName' => 'summary', 'content' => '{{question_3}}', 'style' => ['padding' => '16px 20px', 'cursor' => 'pointer', 'font-weight' => '600', 'color' => '#2d3748', 'list-style' => 'none']],
                                ['tagName' => 'p', 'content' => '{{answer_3}}', 'style' => ['padding' => '0 20px 16px', 'color' => '#718096', 'line-height' => '1.6', 'margin' => '0']],
                            ],
                        ],
                    ],
                ],
                'default_data' => [
                    'question_1' => 'What services do you offer?',
                    'answer_1' => 'We offer a comprehensive range of services tailored to meet your specific needs. Contact us for a detailed consultation.',
                    'question_2' => 'How much does it cost?',
                    'answer_2' => 'Our pricing varies based on the scope of work. We offer competitive rates and free initial quotes for all potential clients.',
                    'question_3' => 'How do I get started?',
                    'answer_3' => 'Getting started is easy! Simply contact us through our website or give us a call. We will guide you through the entire process.',
                ],
            ]
        );
    }

    private function createCtaSection(): void
    {
        BuilderBlock::updateOrCreate(
            ['name' => 'CTA Section', 'is_system' => true],
            [
                'tenant_id' => null,
                'category' => 'conversion',
                'component_json' => [
                    'type' => 'cta-section',
                    'tagName' => 'section',
                    'classes' => ['cta-block'],
                    'style' => [
                        'background' => 'linear-gradient(135deg, #1a202c 0%, #2d3748 100%)',
                        'color' => '#ffffff',
                        'padding' => '60px 40px',
                        'text-align' => 'center',
                        'border-radius' => '12px',
                        'margin-bottom' => '24px',
                    ],
                    'components' => [
                        [
                            'tagName' => 'h2',
                            'content' => '{{cta_heading}}',
                            'style' => ['font-size' => '2rem', 'font-weight' => '800', 'margin-bottom' => '12px'],
                        ],
                        [
                            'tagName' => 'p',
                            'content' => '{{cta_description}}',
                            'style' => ['font-size' => '1.1rem', 'opacity' => '0.85', 'max-width' => '500px', 'margin' => '0 auto 28px auto', 'line-height' => '1.6'],
                        ],
                        [
                            'type' => 'link',
                            'tagName' => 'a',
                            'classes' => ['cta-button'],
                            'attributes' => ['href' => '{{cta_url}}'],
                            'content' => '{{cta_button_text}}',
                            'style' => [
                                'display' => 'inline-block',
                                'background' => '#667eea',
                                'color' => '#ffffff',
                                'padding' => '14px 40px',
                                'border-radius' => '8px',
                                'font-weight' => '700',
                                'font-size' => '1.1rem',
                                'text-decoration' => 'none',
                                'transition' => 'background 0.2s',
                            ],
                        ],
                        [
                            'tagName' => 'p',
                            'content' => '{{cta_footnote}}',
                            'style' => ['font-size' => '0.85rem', 'opacity' => '0.6', 'margin-top' => '16px'],
                        ],
                    ],
                ],
                'default_data' => [
                    'cta_heading' => 'Ready to Get Started?',
                    'cta_description' => 'Join thousands of satisfied customers. Start your free trial today and see the difference.',
                    'cta_button_text' => 'Start Free Trial',
                    'cta_url' => '#signup',
                    'cta_footnote' => 'No credit card required. Cancel anytime.',
                ],
            ]
        );
    }

    private function createAdSlot(): void
    {
        BuilderBlock::updateOrCreate(
            ['name' => 'Ad Slot', 'is_system' => true],
            [
                'tenant_id' => null,
                'category' => 'monetization',
                'component_json' => [
                    'type' => 'ad-slot',
                    'tagName' => 'div',
                    'classes' => ['ad-slot-block'],
                    'style' => [
                        'background' => '#f7fafc',
                        'border' => '2px dashed #cbd5e0',
                        'border-radius' => '8px',
                        'padding' => '24px',
                        'text-align' => 'center',
                        'margin' => '24px 0',
                        'min-height' => '100px',
                    ],
                    'components' => [
                        [
                            'tagName' => 'div',
                            'classes' => ['ad-container'],
                            'attributes' => [
                                'data-ad-slot' => '{{ad_slot_id}}',
                                'data-ad-format' => '{{ad_format}}',
                                'data-ad-type' => '{{ad_type}}',
                            ],
                            'style' => ['min-height' => '90px', 'display' => 'flex', 'align-items' => 'center', 'justify-content' => 'center'],
                            'components' => [
                                [
                                    'tagName' => 'p',
                                    'classes' => ['ad-placeholder-text'],
                                    'content' => 'Ad Space: {{ad_format}} ({{ad_type}})',
                                    'style' => ['color' => '#a0aec0', 'font-size' => '0.9rem', 'margin' => '0'],
                                ],
                            ],
                        ],
                    ],
                ],
                'default_data' => [
                    'ad_slot_id' => 'auto',
                    'ad_format' => 'responsive',
                    'ad_type' => 'auto',
                ],
            ]
        );
    }

    private function createTestimonialSlider(): void
    {
        BuilderBlock::updateOrCreate(
            ['name' => 'Testimonial Slider', 'is_system' => true],
            [
                'tenant_id' => null,
                'category' => 'social-proof',
                'component_json' => [
                    'type' => 'testimonial-slider',
                    'tagName' => 'div',
                    'classes' => ['testimonial-section'],
                    'style' => [
                        'padding' => '40px 24px',
                        'margin-bottom' => '24px',
                    ],
                    'components' => [
                        [
                            'tagName' => 'h2',
                            'content' => 'What Our Customers Say',
                            'style' => ['text-align' => 'center', 'margin-bottom' => '32px', 'color' => '#1a202c', 'font-size' => '1.75rem'],
                        ],
                        [
                            'tagName' => 'div',
                            'classes' => ['testimonials-grid'],
                            'style' => [
                                'display' => 'grid',
                                'grid-template-columns' => 'repeat(3, 1fr)',
                                'gap' => '24px',
                            ],
                            'components' => [
                                [
                                    'tagName' => 'div',
                                    'classes' => ['testimonial-card'],
                                    'style' => [
                                        'background' => '#ffffff',
                                        'border' => '1px solid #e2e8f0',
                                        'border-radius' => '12px',
                                        'padding' => '24px',
                                    ],
                                    'components' => [
                                        ['tagName' => 'div', 'content' => '{{stars_1}}', 'style' => ['color' => '#ecc94b', 'font-size' => '1.2rem', 'margin-bottom' => '12px']],
                                        ['tagName' => 'p', 'content' => '{{testimonial_1}}', 'style' => ['color' => '#4a5568', 'line-height' => '1.6', 'font-style' => 'italic']],
                                        ['tagName' => 'p', 'content' => '- {{author_1}}', 'style' => ['color' => '#1a202c', 'font-weight' => '700', 'margin-bottom' => '0']],
                                        ['tagName' => 'p', 'content' => '{{role_1}}', 'style' => ['color' => '#a0aec0', 'font-size' => '0.85rem', 'margin-top' => '4px']],
                                    ],
                                ],
                                [
                                    'tagName' => 'div',
                                    'classes' => ['testimonial-card'],
                                    'style' => [
                                        'background' => '#ffffff',
                                        'border' => '1px solid #e2e8f0',
                                        'border-radius' => '12px',
                                        'padding' => '24px',
                                    ],
                                    'components' => [
                                        ['tagName' => 'div', 'content' => '{{stars_2}}', 'style' => ['color' => '#ecc94b', 'font-size' => '1.2rem', 'margin-bottom' => '12px']],
                                        ['tagName' => 'p', 'content' => '{{testimonial_2}}', 'style' => ['color' => '#4a5568', 'line-height' => '1.6', 'font-style' => 'italic']],
                                        ['tagName' => 'p', 'content' => '- {{author_2}}', 'style' => ['color' => '#1a202c', 'font-weight' => '700', 'margin-bottom' => '0']],
                                        ['tagName' => 'p', 'content' => '{{role_2}}', 'style' => ['color' => '#a0aec0', 'font-size' => '0.85rem', 'margin-top' => '4px']],
                                    ],
                                ],
                                [
                                    'tagName' => 'div',
                                    'classes' => ['testimonial-card'],
                                    'style' => [
                                        'background' => '#ffffff',
                                        'border' => '1px solid #e2e8f0',
                                        'border-radius' => '12px',
                                        'padding' => '24px',
                                    ],
                                    'components' => [
                                        ['tagName' => 'div', 'content' => '{{stars_3}}', 'style' => ['color' => '#ecc94b', 'font-size' => '1.2rem', 'margin-bottom' => '12px']],
                                        ['tagName' => 'p', 'content' => '{{testimonial_3}}', 'style' => ['color' => '#4a5568', 'line-height' => '1.6', 'font-style' => 'italic']],
                                        ['tagName' => 'p', 'content' => '- {{author_3}}', 'style' => ['color' => '#1a202c', 'font-weight' => '700', 'margin-bottom' => '0']],
                                        ['tagName' => 'p', 'content' => '{{role_3}}', 'style' => ['color' => '#a0aec0', 'font-size' => '0.85rem', 'margin-top' => '4px']],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'default_data' => [
                    'stars_1' => '5/5',
                    'testimonial_1' => 'Absolutely amazing service! They exceeded all our expectations and delivered on time.',
                    'author_1' => 'Sarah Johnson',
                    'role_1' => 'CEO, TechCorp',
                    'stars_2' => '5/5',
                    'testimonial_2' => 'Professional, reliable, and incredibly talented. I would recommend them to anyone.',
                    'author_2' => 'Mike Chen',
                    'role_2' => 'Marketing Director, GrowthCo',
                    'stars_3' => '4.5/5',
                    'testimonial_3' => 'Great experience from start to finish. The results speak for themselves.',
                    'author_3' => 'Emily Davis',
                    'role_3' => 'Founder, StartupXYZ',
                ],
            ]
        );
    }
}
