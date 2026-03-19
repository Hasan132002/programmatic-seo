<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $plan = $user->plan;

        // Sites with page counts
        $sites = $user->sites()
            ->withCount(['pages', 'publishedPages'])
            ->latest()
            ->get();

        // Page statistics
        $totalPages = $user->pages()->count();
        $publishedPages = $user->pages()->where('status', 'published')->count();
        $draftPages = $user->pages()->where('status', 'draft')->count();
        $generatingPages = $user->pages()->where('status', 'generating')->count();

        // Recent pages (last 5)
        $recentPages = $user->pages()
            ->with('site')
            ->latest()
            ->take(5)
            ->get();

        // AI credits
        $aiCreditsUsed = (int) $user->ai_credits_used;
        $aiCreditsLimit = $plan ? $plan->max_ai_credits_monthly : 0;
        $aiCreditsUnlimited = $plan ? $plan->isUnlimited('max_ai_credits_monthly') : false;

        // Plan limits
        $sitesLimit = $plan ? $plan->max_sites : 0;
        $sitesUnlimited = $plan ? $plan->isUnlimited('max_sites') : false;
        $pagesPerSiteLimit = $plan ? $plan->max_pages_per_site : 0;
        $pagesPerSiteUnlimited = $plan ? $plan->isUnlimited('max_pages_per_site') : false;

        // Page views stats - get site IDs for this user
        $siteIds = $sites->pluck('id');

        $viewsThisMonth = PageView::whereIn('site_id', $siteIds)
            ->where('viewed_at', '>=', Carbon::now()->startOfMonth())
            ->count();

        $viewsThisWeek = PageView::whereIn('site_id', $siteIds)
            ->where('viewed_at', '>=', Carbon::now()->startOfWeek())
            ->count();

        $viewsToday = PageView::whereIn('site_id', $siteIds)
            ->where('viewed_at', '>=', Carbon::now()->startOfDay())
            ->count();

        // Current plan info
        $currentPlan = [
            'name' => $plan?->name ?? 'No Plan',
            'slug' => $plan?->slug ?? 'none',
            'expires_at' => $user->plan_expires_at,
        ];

        return view('app.dashboard', compact(
            'sites',
            'totalPages',
            'publishedPages',
            'draftPages',
            'generatingPages',
            'recentPages',
            'aiCreditsUsed',
            'aiCreditsLimit',
            'aiCreditsUnlimited',
            'sitesLimit',
            'sitesUnlimited',
            'pagesPerSiteLimit',
            'pagesPerSiteUnlimited',
            'viewsThisMonth',
            'viewsThisWeek',
            'viewsToday',
            'currentPlan',
        ));
    }
}
