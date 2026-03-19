<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::updateOrCreate(['slug' => 'free'], [
            'name' => 'Free',
            'price_monthly' => 0,
            'price_yearly' => 0,
            'max_sites' => 1,
            'max_pages_per_site' => 50,
            'max_ai_credits_monthly' => 0,
            'features' => [
                'template_generation' => true,
                'ai_generation' => false,
                'custom_domain' => false,
                'page_builder' => false,
                'ad_management' => false,
                'affiliate_links' => false,
                'api_access' => false,
            ],
            'sort_order' => 1,
        ]);

        Plan::updateOrCreate(['slug' => 'pro'], [
            'name' => 'Pro',
            'price_monthly' => 29.00,
            'price_yearly' => 249.00,
            'max_sites' => 5,
            'max_pages_per_site' => 500,
            'max_ai_credits_monthly' => 1000,
            'features' => [
                'template_generation' => true,
                'ai_generation' => true,
                'custom_domain' => true,
                'page_builder' => true,
                'ad_management' => true,
                'affiliate_links' => true,
                'api_access' => false,
            ],
            'sort_order' => 2,
        ]);

        Plan::updateOrCreate(['slug' => 'enterprise'], [
            'name' => 'Enterprise',
            'price_monthly' => 99.00,
            'price_yearly' => 899.00,
            'max_sites' => -1,
            'max_pages_per_site' => -1,
            'max_ai_credits_monthly' => 10000,
            'features' => [
                'template_generation' => true,
                'ai_generation' => true,
                'custom_domain' => true,
                'page_builder' => true,
                'ad_management' => true,
                'affiliate_links' => true,
                'api_access' => true,
                'white_label' => true,
                'team_members' => true,
            ],
            'sort_order' => 3,
        ]);
    }
}
