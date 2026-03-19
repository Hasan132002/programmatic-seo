<?php

namespace App\Http\Controllers\PublicSite;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Http\Request;

class SitePageController extends Controller
{
    public function show(Request $request, string $slug = '')
    {
        $site = app('currentSite');

        if ($slug === '' || $slug === '/') {
            $page = Page::withoutGlobalScopes()
                ->with('template')
                ->where('site_id', $site->id)
                ->where('status', 'published')
                ->orderBy('created_at')
                ->first();

            if (!$page) {
                return response()->view('public-site.home', compact('site'));
            }
        } else {
            $page = Page::withoutGlobalScopes()
                ->with('template')
                ->where('site_id', $site->id)
                ->where('slug', $slug)
                ->where('status', 'published')
                ->firstOrFail();
        }

        $internalLinks = Page::withoutGlobalScopes()
            ->where('site_id', $site->id)
            ->where('status', 'published')
            ->where('id', '!=', $page->id)
            ->inRandomOrder()
            ->limit(5)
            ->get(['title', 'slug']);

        return response()->view('public-site.page', compact('site', 'page', 'internalLinks'))
            ->header('Cache-Control', 'public, max-age=3600')
            ->header('X-Robots-Tag', 'index, follow');
    }

    public function sitemap(Request $request)
    {
        $site = app('currentSite');

        $pages = Page::withoutGlobalScopes()
            ->where('site_id', $site->id)
            ->where('status', 'published')
            ->select(['slug', 'updated_at', 'priority'])
            ->get();

        return response()
            ->view('public-site.sitemap', compact('site', 'pages'))
            ->header('Content-Type', 'application/xml');
    }

    public function robots(Request $request)
    {
        $site = app('currentSite');
        $sitemapUrl = $site->url . '/sitemap.xml';

        return response("User-agent: *\nAllow: /\n\nSitemap: {$sitemapUrl}")
            ->header('Content-Type', 'text/plain');
    }
}
