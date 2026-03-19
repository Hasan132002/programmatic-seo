<?php

namespace Database\Seeders;

use App\Enums\NicheType;
use App\Models\PageTemplate;
use Illuminate\Database\Seeder;

class SystemTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $this->createCityTemplate();
        $this->createComparisonTemplate();
        $this->createDirectoryTemplate();
        $this->createListicleTemplate();
        $this->createReviewTemplate();
    }

    // =========================================================================
    // 1. City / Location Services Template
    // =========================================================================
    private function createCityTemplate(): void
    {
        PageTemplate::updateOrCreate(
            ['slug' => 'city-services', 'is_system' => true],
            [
                'tenant_id' => null,
                'site_id' => null,
                'name' => 'City Services Page',
                'niche_type' => NicheType::City,
                'variable_schema' => [
                    'city_name' => ['type' => 'string', 'required' => true, 'label' => 'City Name'],
                    'state' => ['type' => 'string', 'required' => true, 'label' => 'State'],
                    'service_name' => ['type' => 'string', 'required' => true, 'label' => 'Service Name'],
                    'population' => ['type' => 'string', 'required' => false, 'label' => 'Population'],
                    'description' => ['type' => 'text', 'required' => false, 'label' => 'City Description'],
                    'phone' => ['type' => 'string', 'required' => false, 'label' => 'Phone Number'],
                    'rating' => ['type' => 'string', 'required' => false, 'label' => 'Average Rating'],
                    'num_providers' => ['type' => 'string', 'required' => false, 'label' => 'Number of Providers'],
                    'top_provider' => ['type' => 'string', 'required' => false, 'label' => 'Top Provider Name'],
                ],
                'layout_html' => <<<'HTML'
<div class="tpl-city">
    <!-- Hero Section -->
    <div class="city-hero">
        <div class="city-hero-badge">📍 Local Services Guide</div>
        <h2>Best {{service_name}} in {{city_name}}, {{state}}</h2>
        <p class="city-hero-sub">Trusted, reviewed, and recommended by locals. Find the top-rated {{service_name}} providers serving {{city_name}} and surrounding areas.</p>
        <div class="city-hero-stats">
            <div class="city-stat">
                <div class="city-stat-num">{{num_providers}}</div>
                <div class="city-stat-label">Providers</div>
            </div>
            <div class="city-stat">
                <div class="city-stat-num">{{rating}}</div>
                <div class="city-stat-label">Avg Rating</div>
            </div>
            <div class="city-stat">
                <div class="city-stat-num">{{population}}</div>
                <div class="city-stat-label">Population</div>
            </div>
        </div>
    </div>

    <!-- About Section -->
    <div class="city-section">
        <h2>About {{service_name}} in {{city_name}}</h2>
        <p>{{city_name}} is a thriving city in {{state}} with a population of {{population}}. Residents have access to top-quality {{service_name}} providers who are dedicated to delivering exceptional results.</p>
        <p>{{description}}</p>
    </div>

    <!-- Top Provider Highlight -->
    <div class="city-highlight">
        <div class="city-highlight-badge">⭐ Top Rated</div>
        <h3>{{top_provider}}</h3>
        <p>Serving {{city_name}}, {{state}} with a {{rating}}/5 rating from verified customers. Call <strong>{{phone}}</strong> for a free consultation.</p>
        <a href="tel:{{phone}}" class="city-cta-btn">📞 Call Now: {{phone}}</a>
    </div>

    <!-- Why Choose Section -->
    <div class="city-section">
        <h2>Why Choose Local {{service_name}} in {{city_name}}?</h2>
        <div class="city-benefits">
            <div class="city-benefit">
                <div class="city-benefit-icon">✅</div>
                <h4>Licensed & Insured</h4>
                <p>All providers are fully licensed to operate in {{state}} and carry comprehensive insurance.</p>
            </div>
            <div class="city-benefit">
                <div class="city-benefit-icon">⚡</div>
                <h4>Fast Response</h4>
                <p>Local {{city_name}} providers offer same-day and emergency services when you need them most.</p>
            </div>
            <div class="city-benefit">
                <div class="city-benefit-icon">💰</div>
                <h4>Competitive Pricing</h4>
                <p>Get free quotes and compare prices from the best {{service_name}} in {{city_name}}.</p>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="city-cta">
        <h2>Ready to Get Started?</h2>
        <p>Connect with the best {{service_name}} in {{city_name}}, {{state}} today.</p>
        <a href="tel:{{phone}}" class="city-cta-btn-lg">Call Now: {{phone}}</a>
    </div>
</div>
HTML,
                'layout_css' => <<<'CSS'
.tpl-city { max-width: 100%; }
.city-hero {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #a855f7 100%);
    color: white; padding: 56px 40px; border-radius: 16px; text-align: center; margin-bottom: 32px;
}
.city-hero h2 { font-size: 2.2rem; font-weight: 800; margin-bottom: 12px; line-height: 1.2; }
.city-hero-badge { display: inline-block; background: rgba(255,255,255,0.2); padding: 6px 16px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; margin-bottom: 16px; backdrop-filter: blur(4px); }
.city-hero-sub { font-size: 1.1rem; opacity: 0.92; max-width: 600px; margin: 0 auto 32px; line-height: 1.6; }
.city-hero-stats { display: flex; justify-content: center; gap: 40px; flex-wrap: wrap; }
.city-stat { text-align: center; }
.city-stat-num { font-size: 2rem; font-weight: 800; }
.city-stat-label { font-size: 0.85rem; opacity: 0.8; font-weight: 500; }
.city-section { margin-bottom: 32px; }
.city-section h2 { font-size: 1.6rem; font-weight: 700; color: #0f172a; margin-bottom: 16px; }
.city-highlight {
    background: linear-gradient(135deg, #f0fdf4, #ecfdf5); border: 1px solid #bbf7d0; border-radius: 16px;
    padding: 32px; margin-bottom: 32px; text-align: center;
}
.city-highlight-badge { display: inline-block; background: #16a34a; color: white; padding: 4px 14px; border-radius: 12px; font-size: 0.8rem; font-weight: 600; margin-bottom: 12px; }
.city-highlight h3 { font-size: 1.4rem; font-weight: 700; color: #0f172a; margin-bottom: 8px; }
.city-highlight p { color: #475569; margin-bottom: 20px; }
.city-cta-btn { display: inline-block; background: #16a34a; color: white; padding: 12px 28px; border-radius: 10px; text-decoration: none; font-weight: 700; font-size: 0.95rem; }
.city-benefits { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 20px; }
.city-benefit { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; text-align: center; }
.city-benefit-icon { font-size: 1.8rem; margin-bottom: 10px; }
.city-benefit h4 { font-size: 1rem; font-weight: 700; color: #0f172a; margin-bottom: 6px; }
.city-benefit p { font-size: 0.9rem; color: #64748b; line-height: 1.5; }
.city-cta {
    background: linear-gradient(135deg, #0f172a, #1e293b); color: white; padding: 48px 40px;
    border-radius: 16px; text-align: center; margin-top: 32px;
}
.city-cta h2 { color: white; font-size: 1.8rem; font-weight: 800; margin-bottom: 10px; }
.city-cta p { opacity: 0.8; margin-bottom: 24px; font-size: 1.05rem; }
.city-cta-btn-lg { display: inline-block; background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; padding: 16px 40px; border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 1.1rem; box-shadow: 0 4px 14px rgba(79,70,229,0.4); }
@media (max-width: 768px) {
    .city-hero { padding: 36px 20px; }
    .city-hero h2 { font-size: 1.6rem; }
    .city-hero-stats { gap: 20px; }
    .city-benefits { grid-template-columns: 1fr; }
}
CSS,
            ]
        );
    }

    // =========================================================================
    // 2. Product Comparison Template
    // =========================================================================
    private function createComparisonTemplate(): void
    {
        PageTemplate::updateOrCreate(
            ['slug' => 'product-comparison', 'is_system' => true],
            [
                'tenant_id' => null,
                'site_id' => null,
                'name' => 'Product Comparison',
                'niche_type' => NicheType::Comparison,
                'variable_schema' => [
                    'product_a' => ['type' => 'string', 'required' => true, 'label' => 'Product A Name'],
                    'product_b' => ['type' => 'string', 'required' => true, 'label' => 'Product B Name'],
                    'category' => ['type' => 'string', 'required' => true, 'label' => 'Category'],
                    'product_a_price' => ['type' => 'string', 'required' => false, 'label' => 'Product A Price'],
                    'product_b_price' => ['type' => 'string', 'required' => false, 'label' => 'Product B Price'],
                    'product_a_rating' => ['type' => 'string', 'required' => false, 'label' => 'Product A Rating'],
                    'product_b_rating' => ['type' => 'string', 'required' => false, 'label' => 'Product B Rating'],
                    'winner' => ['type' => 'string', 'required' => false, 'label' => 'Winner'],
                    'summary' => ['type' => 'text', 'required' => false, 'label' => 'Verdict Summary'],
                ],
                'layout_html' => <<<'HTML'
<div class="tpl-compare">
    <!-- Intro -->
    <div class="cmp-intro">
        <div class="cmp-vs-badge">
            <span class="cmp-vs-name">{{product_a}}</span>
            <span class="cmp-vs-circle">VS</span>
            <span class="cmp-vs-name">{{product_b}}</span>
        </div>
        <p>Trying to decide between <strong>{{product_a}}</strong> and <strong>{{product_b}}</strong>? Our detailed {{category}} comparison breaks down the key differences to help you make the right choice.</p>
    </div>

    <!-- Quick Comparison Cards -->
    <div class="cmp-cards">
        <div class="cmp-card cmp-card-a">
            <div class="cmp-card-header">{{product_a}}</div>
            <div class="cmp-card-price">{{product_a_price}}</div>
            <div class="cmp-card-rating">⭐ {{product_a_rating}}/5</div>
            <div class="cmp-card-cat">{{category}}</div>
        </div>
        <div class="cmp-card cmp-card-b">
            <div class="cmp-card-header">{{product_b}}</div>
            <div class="cmp-card-price">{{product_b_price}}</div>
            <div class="cmp-card-rating">⭐ {{product_b_rating}}/5</div>
            <div class="cmp-card-cat">{{category}}</div>
        </div>
    </div>

    <!-- Comparison Table -->
    <div class="cmp-table-wrap">
        <h2>Side-by-Side Comparison</h2>
        <table class="cmp-table">
            <thead>
                <tr>
                    <th>Feature</th>
                    <th>{{product_a}}</th>
                    <th>{{product_b}}</th>
                </tr>
            </thead>
            <tbody>
                <tr><td><strong>Price</strong></td><td>{{product_a_price}}</td><td>{{product_b_price}}</td></tr>
                <tr><td><strong>Rating</strong></td><td>{{product_a_rating}}/5</td><td>{{product_b_rating}}/5</td></tr>
                <tr><td><strong>Category</strong></td><td colspan="2" style="text-align:center;">{{category}}</td></tr>
            </tbody>
        </table>
    </div>

    <!-- Verdict -->
    <div class="cmp-verdict">
        <div class="cmp-verdict-badge">🏆 Our Verdict</div>
        <h2>Winner: {{winner}}</h2>
        <p>{{summary}}</p>
    </div>
</div>
HTML,
                'layout_css' => <<<'CSS'
.tpl-compare { max-width: 100%; }
.cmp-intro { text-align: center; margin-bottom: 32px; }
.cmp-intro p { font-size: 1.05rem; color: #475569; max-width: 600px; margin: 0 auto; line-height: 1.7; }
.cmp-vs-badge { display: flex; align-items: center; justify-content: center; gap: 16px; margin-bottom: 20px; flex-wrap: wrap; }
.cmp-vs-name { font-size: 1.3rem; font-weight: 800; color: #0f172a; }
.cmp-vs-circle { width: 48px; height: 48px; background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; flex-shrink: 0; }
.cmp-cards { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 36px; }
.cmp-card { border-radius: 16px; padding: 28px; text-align: center; border: 2px solid #e2e8f0; transition: border-color 0.2s; }
.cmp-card:hover { border-color: #4f46e5; }
.cmp-card-a { background: linear-gradient(135deg, #eff6ff, #dbeafe); }
.cmp-card-b { background: linear-gradient(135deg, #faf5ff, #f3e8ff); }
.cmp-card-header { font-size: 1.3rem; font-weight: 800; color: #0f172a; margin-bottom: 12px; }
.cmp-card-price { font-size: 2rem; font-weight: 900; color: #0f172a; margin-bottom: 8px; }
.cmp-card-rating { font-size: 1.1rem; font-weight: 600; color: #f59e0b; margin-bottom: 4px; }
.cmp-card-cat { font-size: 0.85rem; color: #94a3b8; font-weight: 500; }
.cmp-table-wrap { margin-bottom: 36px; }
.cmp-table-wrap h2 { font-size: 1.4rem; font-weight: 700; color: #0f172a; margin-bottom: 16px; }
.cmp-table { width: 100%; border-collapse: separate; border-spacing: 0; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; }
.cmp-table thead th { background: #0f172a; color: white; padding: 14px 18px; text-align: left; font-weight: 600; font-size: 0.9rem; }
.cmp-table tbody td { padding: 14px 18px; border-bottom: 1px solid #f1f5f9; }
.cmp-table tbody tr:last-child td { border-bottom: none; }
.cmp-table tbody tr:hover { background: #f8fafc; }
.cmp-verdict {
    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white;
    padding: 40px; border-radius: 16px; text-align: center; margin-top: 12px;
}
.cmp-verdict-badge { display: inline-block; background: rgba(255,255,255,0.2); padding: 6px 16px; border-radius: 20px; font-size: 0.85rem; font-weight: 600; margin-bottom: 14px; }
.cmp-verdict h2 { color: white; font-size: 1.8rem; font-weight: 800; margin-bottom: 12px; }
.cmp-verdict p { opacity: 0.92; max-width: 550px; margin: 0 auto; line-height: 1.7; font-size: 1.05rem; }
@media (max-width: 768px) {
    .cmp-cards { grid-template-columns: 1fr; }
    .cmp-card-price { font-size: 1.5rem; }
    .cmp-verdict { padding: 28px 20px; }
}
CSS,
            ]
        );
    }

    // =========================================================================
    // 3. Business Directory Listing Template
    // =========================================================================
    private function createDirectoryTemplate(): void
    {
        PageTemplate::updateOrCreate(
            ['slug' => 'business-directory', 'is_system' => true],
            [
                'tenant_id' => null,
                'site_id' => null,
                'name' => 'Business Directory Listing',
                'niche_type' => NicheType::Directory,
                'variable_schema' => [
                    'business_name' => ['type' => 'string', 'required' => true, 'label' => 'Business Name'],
                    'category' => ['type' => 'string', 'required' => true, 'label' => 'Category'],
                    'city' => ['type' => 'string', 'required' => true, 'label' => 'City'],
                    'address' => ['type' => 'string', 'required' => false, 'label' => 'Address'],
                    'phone' => ['type' => 'string', 'required' => false, 'label' => 'Phone'],
                    'website' => ['type' => 'string', 'required' => false, 'label' => 'Website URL'],
                    'description' => ['type' => 'text', 'required' => false, 'label' => 'Description'],
                    'hours' => ['type' => 'string', 'required' => false, 'label' => 'Business Hours'],
                    'rating' => ['type' => 'string', 'required' => false, 'label' => 'Rating'],
                ],
                'layout_html' => <<<'HTML'
<div class="tpl-directory">
    <!-- Business Header -->
    <div class="dir-header">
        <div class="dir-header-top">
            <span class="dir-category-badge">{{category}}</span>
            <span class="dir-rating-badge">⭐ {{rating}}/5</span>
        </div>
        <h2>{{business_name}}</h2>
        <p class="dir-location">📍 {{address}}, {{city}}</p>
    </div>

    <!-- Info Grid -->
    <div class="dir-grid">
        <div class="dir-main">
            <h3>About {{business_name}}</h3>
            <p>{{description}}</p>

            <h3>Services</h3>
            <p>{{business_name}} is a leading {{category}} provider in {{city}}, offering comprehensive services with consistently high customer satisfaction ratings of {{rating}}/5.</p>

            <h3>Why Choose {{business_name}}?</h3>
            <ul>
                <li>Trusted {{category}} provider in {{city}}</li>
                <li>{{rating}}/5 average customer rating</li>
                <li>Convenient location at {{address}}</li>
                <li>Professional, experienced team</li>
            </ul>
        </div>

        <div class="dir-sidebar">
            <div class="dir-contact-card">
                <h4>Contact Information</h4>
                <div class="dir-contact-item">
                    <span class="dir-contact-label">📍 Address</span>
                    <span>{{address}}, {{city}}</span>
                </div>
                <div class="dir-contact-item">
                    <span class="dir-contact-label">📞 Phone</span>
                    <a href="tel:{{phone}}">{{phone}}</a>
                </div>
                <div class="dir-contact-item">
                    <span class="dir-contact-label">🌐 Website</span>
                    <a href="{{website}}" target="_blank" rel="nofollow">Visit Website</a>
                </div>
                <div class="dir-contact-item">
                    <span class="dir-contact-label">🕐 Hours</span>
                    <span>{{hours}}</span>
                </div>
                <a href="tel:{{phone}}" class="dir-call-btn">📞 Call Now</a>
            </div>
        </div>
    </div>
</div>
HTML,
                'layout_css' => <<<'CSS'
.tpl-directory { max-width: 100%; }
.dir-header {
    background: linear-gradient(135deg, #0f172a, #1e293b); color: white;
    padding: 36px; border-radius: 16px; margin-bottom: 24px;
}
.dir-header-top { display: flex; gap: 10px; margin-bottom: 14px; flex-wrap: wrap; }
.dir-category-badge { background: rgba(99,102,241,0.8); padding: 5px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
.dir-rating-badge { background: rgba(245,158,11,0.2); color: #fbbf24; padding: 5px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
.dir-header h2 { font-size: 2rem; font-weight: 800; margin-bottom: 8px; color: white; }
.dir-location { opacity: 0.8; font-size: 1rem; }
.dir-grid { display: grid; grid-template-columns: 1fr 340px; gap: 28px; }
.dir-main h3 { font-size: 1.3rem; font-weight: 700; color: #0f172a; margin: 24px 0 10px; }
.dir-main h3:first-child { margin-top: 0; }
.dir-main ul { padding-left: 20px; }
.dir-main li { margin-bottom: 8px; color: #475569; }
.dir-contact-card {
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 24px; position: sticky; top: 80px;
}
.dir-contact-card h4 { font-size: 1.1rem; font-weight: 700; color: #0f172a; margin-bottom: 16px; }
.dir-contact-item { padding: 12px 0; border-bottom: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 4px; }
.dir-contact-item:last-of-type { border-bottom: none; }
.dir-contact-label { font-size: 0.8rem; font-weight: 600; color: #94a3b8; }
.dir-contact-item a { color: #4f46e5; text-decoration: none; font-weight: 500; }
.dir-call-btn {
    display: block; text-align: center; background: #16a34a; color: white; padding: 14px;
    border-radius: 12px; text-decoration: none; font-weight: 700; margin-top: 20px;
}
@media (max-width: 768px) {
    .dir-grid { grid-template-columns: 1fr; }
    .dir-header h2 { font-size: 1.5rem; }
    .dir-contact-card { position: static; }
}
CSS,
            ]
        );
    }

    // =========================================================================
    // 4. Listicle / Top N Template
    // =========================================================================
    private function createListicleTemplate(): void
    {
        PageTemplate::updateOrCreate(
            ['slug' => 'top-listicle', 'is_system' => true],
            [
                'tenant_id' => null,
                'site_id' => null,
                'name' => 'Top N Listicle',
                'niche_type' => NicheType::Custom,
                'variable_schema' => [
                    'list_title' => ['type' => 'string', 'required' => true, 'label' => 'List Title (e.g. "Best Coffee Shops")'],
                    'location' => ['type' => 'string', 'required' => false, 'label' => 'Location'],
                    'year' => ['type' => 'string', 'required' => false, 'label' => 'Year'],
                    'intro' => ['type' => 'text', 'required' => false, 'label' => 'Introduction Text'],
                    'item_1_name' => ['type' => 'string', 'required' => true, 'label' => 'Item 1 Name'],
                    'item_1_desc' => ['type' => 'text', 'required' => false, 'label' => 'Item 1 Description'],
                    'item_1_rating' => ['type' => 'string', 'required' => false, 'label' => 'Item 1 Rating'],
                    'item_2_name' => ['type' => 'string', 'required' => true, 'label' => 'Item 2 Name'],
                    'item_2_desc' => ['type' => 'text', 'required' => false, 'label' => 'Item 2 Description'],
                    'item_2_rating' => ['type' => 'string', 'required' => false, 'label' => 'Item 2 Rating'],
                    'item_3_name' => ['type' => 'string', 'required' => true, 'label' => 'Item 3 Name'],
                    'item_3_desc' => ['type' => 'text', 'required' => false, 'label' => 'Item 3 Description'],
                    'item_3_rating' => ['type' => 'string', 'required' => false, 'label' => 'Item 3 Rating'],
                    'conclusion' => ['type' => 'text', 'required' => false, 'label' => 'Conclusion'],
                ],
                'layout_html' => <<<'HTML'
<div class="tpl-listicle">
    <div class="lst-intro">
        <div class="lst-intro-badge">📋 {{year}} Guide</div>
        <p>{{intro}}</p>
    </div>

    <!-- List Items -->
    <div class="lst-items">
        <div class="lst-item">
            <div class="lst-item-rank">1</div>
            <div class="lst-item-content">
                <div class="lst-item-top">
                    <h3>{{item_1_name}}</h3>
                    <span class="lst-item-rating">⭐ {{item_1_rating}}/5</span>
                </div>
                <p>{{item_1_desc}}</p>
                <div class="lst-item-tag">🏆 Top Pick</div>
            </div>
        </div>
        <div class="lst-item">
            <div class="lst-item-rank">2</div>
            <div class="lst-item-content">
                <div class="lst-item-top">
                    <h3>{{item_2_name}}</h3>
                    <span class="lst-item-rating">⭐ {{item_2_rating}}/5</span>
                </div>
                <p>{{item_2_desc}}</p>
                <div class="lst-item-tag">👍 Highly Rated</div>
            </div>
        </div>
        <div class="lst-item">
            <div class="lst-item-rank">3</div>
            <div class="lst-item-content">
                <div class="lst-item-top">
                    <h3>{{item_3_name}}</h3>
                    <span class="lst-item-rating">⭐ {{item_3_rating}}/5</span>
                </div>
                <p>{{item_3_desc}}</p>
                <div class="lst-item-tag">💡 Great Value</div>
            </div>
        </div>
    </div>

    <!-- Conclusion -->
    <div class="lst-conclusion">
        <h2>Final Thoughts</h2>
        <p>{{conclusion}}</p>
    </div>
</div>
HTML,
                'layout_css' => <<<'CSS'
.tpl-listicle { max-width: 100%; }
.lst-intro { margin-bottom: 28px; }
.lst-intro-badge { display: inline-block; background: #ede9fe; color: #7c3aed; padding: 6px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; margin-bottom: 14px; }
.lst-intro p { font-size: 1.05rem; color: #475569; line-height: 1.7; }
.lst-items { display: flex; flex-direction: column; gap: 16px; margin-bottom: 32px; }
.lst-item {
    display: flex; gap: 20px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px;
    padding: 24px; transition: all 0.2s; align-items: flex-start;
}
.lst-item:hover { border-color: #4f46e5; box-shadow: 0 4px 12px rgba(0,0,0,0.06); }
.lst-item-rank {
    width: 48px; height: 48px; background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white;
    border-radius: 14px; display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; font-weight: 900; flex-shrink: 0;
}
.lst-item-content { flex: 1; }
.lst-item-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 8px; }
.lst-item-content h3 { font-size: 1.2rem; font-weight: 700; color: #0f172a; margin: 0; }
.lst-item-rating { font-size: 0.9rem; font-weight: 600; color: #f59e0b; }
.lst-item-content p { color: #475569; line-height: 1.6; font-size: 0.95rem; margin-bottom: 10px; }
.lst-item-tag { display: inline-block; background: #f0fdf4; color: #16a34a; padding: 3px 12px; border-radius: 10px; font-size: 0.8rem; font-weight: 600; }
.lst-item:first-child .lst-item-rank { background: linear-gradient(135deg, #f59e0b, #f97316); }
.lst-conclusion {
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 16px; padding: 28px;
}
.lst-conclusion h2 { font-size: 1.3rem; font-weight: 700; color: #0f172a; margin-bottom: 10px; }
.lst-conclusion p { color: #475569; line-height: 1.7; }
CSS,
            ]
        );
    }

    // =========================================================================
    // 5. Review / Roundup Template
    // =========================================================================
    private function createReviewTemplate(): void
    {
        PageTemplate::updateOrCreate(
            ['slug' => 'product-review', 'is_system' => true],
            [
                'tenant_id' => null,
                'site_id' => null,
                'name' => 'Product Review',
                'niche_type' => NicheType::Custom,
                'variable_schema' => [
                    'product_name' => ['type' => 'string', 'required' => true, 'label' => 'Product Name'],
                    'category' => ['type' => 'string', 'required' => true, 'label' => 'Category'],
                    'rating' => ['type' => 'string', 'required' => true, 'label' => 'Overall Rating'],
                    'price' => ['type' => 'string', 'required' => false, 'label' => 'Price'],
                    'pros' => ['type' => 'text', 'required' => false, 'label' => 'Pros (comma separated)'],
                    'cons' => ['type' => 'text', 'required' => false, 'label' => 'Cons (comma separated)'],
                    'summary' => ['type' => 'text', 'required' => false, 'label' => 'Review Summary'],
                    'verdict' => ['type' => 'text', 'required' => false, 'label' => 'Final Verdict'],
                    'buy_url' => ['type' => 'string', 'required' => false, 'label' => 'Buy Link URL'],
                ],
                'layout_html' => <<<'HTML'
<div class="tpl-review">
    <!-- Review Header -->
    <div class="rev-header">
        <span class="rev-cat-badge">{{category}}</span>
        <div class="rev-score-box">
            <div class="rev-score">{{rating}}</div>
            <div class="rev-score-label">out of 5</div>
        </div>
    </div>

    <!-- Summary -->
    <div class="rev-summary">
        <p>{{summary}}</p>
        <div class="rev-price-tag">
            <span class="rev-price-label">Price:</span>
            <span class="rev-price-val">{{price}}</span>
        </div>
    </div>

    <!-- Pros & Cons -->
    <div class="rev-proscons">
        <div class="rev-pros">
            <h3>👍 What We Like</h3>
            <p>{{pros}}</p>
        </div>
        <div class="rev-cons">
            <h3>👎 What Could Be Better</h3>
            <p>{{cons}}</p>
        </div>
    </div>

    <!-- Verdict -->
    <div class="rev-verdict">
        <h2>Our Verdict</h2>
        <p>{{verdict}}</p>
        <a href="{{buy_url}}" class="rev-buy-btn" target="_blank" rel="nofollow noopener">Check Current Price →</a>
    </div>
</div>
HTML,
                'layout_css' => <<<'CSS'
.tpl-review { max-width: 100%; }
.rev-header {
    display: flex; justify-content: space-between; align-items: center;
    background: linear-gradient(135deg, #0f172a, #1e293b); color: white;
    padding: 32px; border-radius: 16px; margin-bottom: 24px;
}
.rev-cat-badge { background: rgba(99,102,241,0.8); padding: 5px 14px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
.rev-score-box { text-align: center; }
.rev-score { font-size: 3rem; font-weight: 900; color: #fbbf24; line-height: 1; }
.rev-score-label { font-size: 0.8rem; color: #94a3b8; font-weight: 500; }
.rev-summary { margin-bottom: 28px; }
.rev-summary p { font-size: 1.05rem; color: #475569; line-height: 1.7; margin-bottom: 16px; }
.rev-price-tag {
    display: inline-flex; align-items: center; gap: 8px; background: #f0fdf4; border: 1px solid #bbf7d0;
    padding: 8px 18px; border-radius: 10px;
}
.rev-price-label { font-size: 0.85rem; color: #64748b; font-weight: 500; }
.rev-price-val { font-size: 1.3rem; font-weight: 800; color: #16a34a; }
.rev-proscons { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 28px; }
.rev-pros, .rev-cons {
    padding: 24px; border-radius: 16px; border: 1px solid;
}
.rev-pros { background: #f0fdf4; border-color: #bbf7d0; }
.rev-cons { background: #fef2f2; border-color: #fecaca; }
.rev-pros h3, .rev-cons h3 { font-size: 1.05rem; font-weight: 700; margin-bottom: 10px; }
.rev-pros h3 { color: #16a34a; }
.rev-cons h3 { color: #dc2626; }
.rev-pros p { color: #166534; line-height: 1.6; }
.rev-cons p { color: #991b1b; line-height: 1.6; }
.rev-verdict {
    background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white;
    padding: 36px; border-radius: 16px; text-align: center;
}
.rev-verdict h2 { color: white; font-size: 1.5rem; font-weight: 800; margin-bottom: 12px; }
.rev-verdict p { opacity: 0.92; max-width: 550px; margin: 0 auto 20px; line-height: 1.7; }
.rev-buy-btn {
    display: inline-block; background: white; color: #4f46e5; padding: 14px 32px;
    border-radius: 12px; text-decoration: none; font-weight: 700; font-size: 1rem;
    box-shadow: 0 4px 14px rgba(0,0,0,0.15);
}
@media (max-width: 768px) {
    .rev-header { flex-direction: column; gap: 16px; text-align: center; }
    .rev-proscons { grid-template-columns: 1fr; }
}
CSS,
            ]
        );
    }
}
