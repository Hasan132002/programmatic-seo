<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Plan;
use App\Models\Site;
use App\Models\User;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $stats = [
            'total_users' => User::count(),
            'total_sites' => Site::withoutGlobalScopes()->count(),
            'total_pages' => Page::withoutGlobalScopes()->count(),
            'published_pages' => Page::withoutGlobalScopes()->where('status', 'published')->count(),
        ];

        // Estimate monthly revenue based on active paid users
        $revenue = User::whereNotNull('plan_id')
            ->join('plans', 'users.plan_id', '=', 'plans.id')
            ->sum('plans.price_monthly');

        $recentUsers = User::with('plan')
            ->withCount('sites')
            ->latest()
            ->take(5)
            ->get();

        $recentSites = Site::withoutGlobalScopes()
            ->with('tenant')
            ->withCount('pages')
            ->latest()
            ->take(5)
            ->get();

        $systemInfo = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'cache_driver' => config('cache.default'),
            'queue_driver' => config('queue.default'),
            'debug_mode' => config('app.debug'),
        ];

        return view('admin.dashboard', compact('stats', 'revenue', 'recentUsers', 'recentSites', 'systemInfo'));
    }
}
