<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use App\Models\Site;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function index()
    {
        return view('app.sites.index');
    }

    public function create()
    {
        if (!auth()->user()->canCreateSite()) {
            return redirect()->route('app.sites.index')
                ->with('error', 'You have reached your site limit. Please upgrade your plan.');
        }

        return view('app.sites.create');
    }

    public function show(Site $site)
    {
        $site->loadCount('pages');

        $publishedCount = $site->publishedPages()->count();
        $draftCount = $site->pages()->where('status', 'draft')->count();

        $recentPages = $site->pages()
            ->latest()
            ->take(5)
            ->get(['id', 'title', 'slug', 'status', 'updated_at']);

        // Page views for the last 7 days
        $viewsCount = PageView::where('site_id', $site->id)
            ->where('viewed_at', '>=', now()->subDays(7))
            ->count();

        // Mini chart data for last 7 days
        $chartData = PageView::where('site_id', $site->id)
            ->where('viewed_at', '>=', now()->subDays(7))
            ->selectRaw('DATE(viewed_at) as date, COUNT(*) as views')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn($v) => [
                'date' => \Carbon\Carbon::parse($v->date)->format('M d'),
                'views' => (int) $v->views,
            ])
            ->toArray();

        return view('app.sites.show', compact(
            'site', 'publishedCount', 'draftCount', 'recentPages', 'viewsCount', 'chartData'
        ));
    }

    public function edit(Site $site)
    {
        return view('app.sites.edit', compact('site'));
    }
}
