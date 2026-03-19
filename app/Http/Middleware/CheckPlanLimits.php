<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanLimits
{
    /**
     * Handle an incoming request.
     *
     * Check if the user has exceeded their plan limits for a given resource.
     * Usage in routes: middleware('plan-limits:sites') or middleware('plan-limits:pages') or middleware('plan-limits:ai')
     */
    public function handle(Request $request, Closure $next, string $resource = ''): Response
    {
        $user = $request->user();
        if (!$user) {
            return $next($request);
        }

        $plan = $user->plan;
        if (!$plan) {
            return $next($request);
        }

        $exceeded = false;
        $message = '';

        switch ($resource) {
            case 'sites':
                if ($plan->max_sites > 0 && $user->sites()->count() >= $plan->max_sites) {
                    $exceeded = true;
                    $message = "You've reached your plan limit of {$plan->max_sites} sites. Please upgrade your plan to create more.";
                }
                break;

            case 'pages':
                $site = $request->route('site');
                if ($site && $plan->max_pages_per_site > 0 && $site->pages()->count() >= $plan->max_pages_per_site) {
                    $exceeded = true;
                    $message = "You've reached your plan limit of {$plan->max_pages_per_site} pages per site. Please upgrade your plan to add more.";
                }
                break;

            case 'ai':
                if ($plan->max_ai_credits_monthly == 0) {
                    $exceeded = true;
                    $message = "AI content generation is not available on the Free plan. Please upgrade to Pro or Enterprise.";
                } elseif ($plan->max_ai_credits_monthly > 0 && $user->ai_credits_used >= $plan->max_ai_credits_monthly) {
                    $exceeded = true;
                    $message = "You've used all {$plan->max_ai_credits_monthly} AI credits for this month. Credits reset on your next billing date. Please upgrade for more.";
                }
                break;
        }

        if ($exceeded) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'upgrade_url' => route('app.billing'),
                ], 403);
            }

            return redirect()->route('app.billing')->with('error', $message);
        }

        return $next($request);
    }
}
