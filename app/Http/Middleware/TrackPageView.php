<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackPageView
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track successful responses
        if ($response->getStatusCode() !== 200) {
            return $response;
        }

        $site = app('currentSite');
        $page = $request->route('page');

        if ($site) {
            try {
                PageView::create([
                    'site_id' => $site->id,
                    'page_id' => is_object($page) ? $page->id : null,
                    'ip_hash' => hash('sha256', $request->ip() . config('app.key')),
                    'user_agent' => substr($request->userAgent() ?? '', 0, 500),
                    'referer' => substr($request->header('referer', ''), 0, 500),
                    'country' => null, // Can be added with GeoIP later
                    'viewed_at' => now(),
                ]);
            } catch (\Exception $e) {
                // Silently fail - don't break page for analytics
                \Log::warning('PageView tracking failed: ' . $e->getMessage());
            }
        }

        return $response;
    }
}
