<?php

namespace App\Http\Middleware;

use App\Http\Controllers\PublicSite\SitePageController;
use App\Models\Site;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DetectPublicSite
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $appHost = parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';

        // If the request host matches the main app host exactly, continue normal routing
        if ($host === $appHost) {
            return $next($request);
        }

        // Check if this is a subdomain of the main app (e.g., best-plumbers.localhost)
        $subdomain = str_replace('.' . $appHost, '', $host);

        // If nothing was stripped, it might be a custom domain
        $isSubdomain = $subdomain !== $host;

        $site = Site::withoutGlobalScopes()
            ->where('is_published', true)
            ->where(function ($query) use ($host, $subdomain, $isSubdomain) {
                $query->where('domain', $host);
                if ($isSubdomain) {
                    $query->orWhere('subdomain', $subdomain);
                }
            })
            ->first();

        if (!$site) {
            return $next($request);
        }

        // Bind the site and dispatch to the public site controller
        app()->instance('currentSite', $site);
        $request->merge(['site' => $site]);

        $path = trim($request->path(), '/');

        if ($path === 'sitemap.xml') {
            return app(SitePageController::class)->sitemap($request);
        }

        if ($path === 'robots.txt') {
            return app(SitePageController::class)->robots($request);
        }

        return app(SitePageController::class)->show($request, $path);
    }
}
