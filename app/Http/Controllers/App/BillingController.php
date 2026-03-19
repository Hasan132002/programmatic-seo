<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Plan;

class BillingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        $currentPlan = $user->plan;

        return view('app.billing.index', compact('plans', 'currentPlan', 'user'));
    }

    public function checkout(Plan $plan)
    {
        $user = auth()->user();

        if ($plan->price_monthly <= 0) {
            // Free plan - just update directly
            $user->update(['plan_id' => $plan->id, 'plan_expires_at' => null]);
            return redirect()->route('app.billing')->with('success', 'Switched to Free plan!');
        }

        return view('app.billing.checkout', compact('plan', 'user'));
    }

    public function success()
    {
        return redirect()->route('app.billing')->with('success', 'Subscription activated successfully!');
    }

    public function cancel()
    {
        return redirect()->route('app.billing')->with('error', 'Checkout was cancelled.');
    }
}
