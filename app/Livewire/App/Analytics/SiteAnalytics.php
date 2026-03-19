<?php

namespace App\Livewire\App\Analytics;

use App\Models\Site;
use App\Models\PageView;
use Livewire\Component;
use Carbon\Carbon;

class SiteAnalytics extends Component
{
    public Site $site;
    public string $period = '7d';
    public array $chartData = [];

    public function mount(Site $site)
    {
        $this->site = $site;
        $this->loadData();
    }

    public function updatedPeriod()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $startDate = $this->getStartDate();

        $views = PageView::where('site_id', $this->site->id)
            ->where('viewed_at', '>=', $startDate)
            ->selectRaw('DATE(viewed_at) as date, COUNT(*) as views, COUNT(DISTINCT ip_hash) as unique_visitors')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $this->chartData = $views->map(fn($v) => [
            'date' => Carbon::parse($v->date)->format('M d'),
            'views' => (int) $v->views,
            'visitors' => (int) $v->unique_visitors,
        ])->toArray();
    }

    protected function getStartDate(): Carbon
    {
        return match($this->period) {
            '24h' => now()->subDay(),
            '7d' => now()->subDays(7),
            '30d' => now()->subDays(30),
            '90d' => now()->subDays(90),
            default => now()->subDays(7),
        };
    }

    public function render()
    {
        $startDate = $this->getStartDate();

        $totalViews = PageView::where('site_id', $this->site->id)
            ->where('viewed_at', '>=', $startDate)
            ->count();

        $uniqueVisitors = PageView::where('site_id', $this->site->id)
            ->where('viewed_at', '>=', $startDate)
            ->distinct('ip_hash')
            ->count('ip_hash');

        $topPages = PageView::where('page_views.site_id', $this->site->id)
            ->where('viewed_at', '>=', now()->subDays(30))
            ->join('pages', 'page_views.page_id', '=', 'pages.id')
            ->selectRaw('pages.title, pages.slug, COUNT(*) as views')
            ->groupBy('pages.id', 'pages.title', 'pages.slug')
            ->orderByDesc('views')
            ->take(10)
            ->get();

        $topReferrers = PageView::where('site_id', $this->site->id)
            ->where('viewed_at', '>=', now()->subDays(30))
            ->whereNotNull('referer')
            ->where('referer', '!=', '')
            ->selectRaw('referer, COUNT(*) as count')
            ->groupBy('referer')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        return view('livewire.app.analytics.site-analytics', compact(
            'totalViews', 'uniqueVisitors', 'topPages', 'topReferrers'
        ));
    }
}
