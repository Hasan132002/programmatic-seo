<?php

namespace App\Http\Middleware;

use App\Models\Site;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveSiteMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();

        $site = Site::withoutGlobalScopes()
            ->where('is_published', true)
            ->where(function ($query) use ($host) {
                $query->where('domain', $host)
                    ->orWhere('subdomain', explode('.', $host)[0]);
            })
            ->first();

        if (!$site) {
            abort(404, 'Site not found.');
        }

        app()->instance('currentSite', $site);
        $request->merge(['site' => $site]);

        return $next($request);
    }
}
