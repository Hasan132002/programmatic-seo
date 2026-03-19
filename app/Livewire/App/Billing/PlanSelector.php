<?php

namespace App\Livewire\App\Billing;

use App\Models\Plan;
use Livewire\Component;

class PlanSelector extends Component
{
    public bool $yearly = false;
    public ?int $currentPlanId = null;

    public function mount(): void
    {
        $this->currentPlanId = auth()->user()->plan_id;
    }

    public function toggleBilling(): void
    {
        $this->yearly = !$this->yearly;
    }

    public function selectPlan(int $planId): void
    {
        $plan = Plan::findOrFail($planId);

        if ($plan->price_monthly <= 0) {
            // Free plan - switch directly
            $user = auth()->user();

            // If user has an active Stripe subscription, cancel it
            if ($user->subscribed('default')) {
                $user->subscription('default')->cancelNow();
            }

            $user->update([
                'plan_id' => $plan->id,
                'plan_expires_at' => null,
            ]);

            $this->currentPlanId = $plan->id;
            session()->flash('success', 'Switched to Free plan successfully!');
            $this->redirect(route('app.billing'), navigate: true);
            return;
        }

        // Paid plan - redirect to checkout
        $this->redirect(
            route('app.billing.checkout', ['plan' => $plan->id]) . ($this->yearly ? '?billing=yearly' : ''),
            navigate: true
        );
    }

    public function render()
    {
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();

        return view('livewire.app.billing.plan-selector', [
            'plans' => $plans,
        ]);
    }
}
