<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $enterprisePlan = Plan::where('slug', 'enterprise')->first();

        User::updateOrCreate(['email' => 'admin@admin.com'], [
            'name' => 'Admin',
            'password' => bcrypt('password'),
            'is_admin' => true,
            'plan_id' => $enterprisePlan?->id,
            'email_verified_at' => now(),
        ]);
    }
}
