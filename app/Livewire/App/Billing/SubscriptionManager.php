<?php

namespace App\Livewire\App\Billing;

use Livewire\Component;

class SubscriptionManager extends Component
{
    public bool $showCancelModal = false;

    public function cancelSubscription(): void
    {
        $user = auth()->user();

        if ($user->subscribed('default')) {
            $user->subscription('default')->cancel();
            session()->flash('success', 'Your subscription has been cancelled. You can continue using the plan until the end of the billing period.');
        }

        $this->showCancelModal = false;
        $this->dispatch('subscription-updated');
    }

    public function resumeSubscription(): void
    {
        $user = auth()->user();

        if ($user->subscription('default')?->onGracePeriod()) {
            $user->subscription('default')->resume();
            session()->flash('success', 'Your subscription has been resumed!');
        }

        $this->dispatch('subscription-updated');
    }

    public function redirectToPortal(): void
    {
        $user = auth()->user();

        if ($user->hasStripeId()) {
            $url = $user->billingPortalUrl(route('app.billing'));
            $this->redirect($url);
        }
    }

    public function render()
    {
        $user = auth()->user();
        $plan = $user->plan;
        $subscription = $user->subscription('default');

        $invoices = [];
        if ($user->hasStripeId()) {
            try {
                $invoices = $user->invoices()->take(5);
            } catch (\Exception $e) {
                $invoices = collect();
            }
        }

        // Usage stats
        $sitesUsed = $user->sites()->count();
        $sitesLimit = $plan?->max_sites ?? 0;
        $aiCreditsUsed = $user->ai_credits_used ?? 0;
        $aiCreditsLimit = $plan?->max_ai_credits_monthly ?? 0;

        // Determine max pages across all sites
        $maxPagesUsed = 0;
        $pagesLimit = $plan?->max_pages_per_site ?? 0;
        foreach ($user->sites as $site) {
            $count = $site->pages()->count();
            if ($count > $maxPagesUsed) {
                $maxPagesUsed = $count;
            }
        }

        return view('livewire.app.billing.subscription-manager', [
            'plan' => $plan,
            'subscription' => $subscription,
            'invoices' => $invoices,
            'sitesUsed' => $sitesUsed,
            'sitesLimit' => $sitesLimit,
            'aiCreditsUsed' => $aiCreditsUsed,
            'aiCreditsLimit' => $aiCreditsLimit,
            'maxPagesUsed' => $maxPagesUsed,
            'pagesLimit' => $pagesLimit,
        ]);
    }
}
