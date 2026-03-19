<?php

namespace App\Livewire\Admin;

use App\Models\Plan;
use Illuminate\Support\Str;
use Livewire\Component;

class PlanManager extends Component
{
    public bool $showModal = false;
    public ?int $editingPlanId = null;
    public ?int $confirmingDeleteId = null;

    // Form fields
    public string $name = '';
    public string $slug = '';
    public string $stripe_price_id = '';
    public string $price_monthly = '0.00';
    public string $price_yearly = '0.00';
    public int $max_sites = 1;
    public int $max_pages_per_site = 50;
    public int $max_ai_credits_monthly = 100;
    public string $features = '';
    public bool $is_active = true;
    public int $sort_order = 0;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:plans,slug,' . ($this->editingPlanId ?? 'NULL'),
            'stripe_price_id' => 'nullable|string|max:255',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'required|numeric|min:0',
            'max_sites' => 'required|integer|min:-1',
            'max_pages_per_site' => 'required|integer|min:-1',
            'max_ai_credits_monthly' => 'required|integer|min:-1',
            'features' => 'nullable|string',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ];
    }

    public function updatedName(string $value): void
    {
        if (!$this->editingPlanId) {
            $this->slug = Str::slug($value);
        }
    }

    public function openCreateModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function openEditModal(int $planId): void
    {
        $plan = Plan::findOrFail($planId);
        $this->editingPlanId = $plan->id;
        $this->name = $plan->name;
        $this->slug = $plan->slug;
        $this->stripe_price_id = $plan->stripe_price_id ?? '';
        $this->price_monthly = (string) $plan->price_monthly;
        $this->price_yearly = (string) $plan->price_yearly;
        $this->max_sites = $plan->max_sites;
        $this->max_pages_per_site = $plan->max_pages_per_site;
        $this->max_ai_credits_monthly = $plan->max_ai_credits_monthly;
        $this->features = $plan->features ? implode("\n", $plan->features) : '';
        $this->is_active = $plan->is_active;
        $this->sort_order = $plan->sort_order ?? 0;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'stripe_price_id' => $this->stripe_price_id ?: null,
            'price_monthly' => $this->price_monthly,
            'price_yearly' => $this->price_yearly,
            'max_sites' => $this->max_sites,
            'max_pages_per_site' => $this->max_pages_per_site,
            'max_ai_credits_monthly' => $this->max_ai_credits_monthly,
            'features' => $this->features
                ? array_map('trim', explode("\n", $this->features))
                : [],
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ];

        if ($this->editingPlanId) {
            Plan::findOrFail($this->editingPlanId)->update($data);
            session()->flash('success', "Plan \"{$this->name}\" updated successfully.");
        } else {
            Plan::create($data);
            session()->flash('success', "Plan \"{$this->name}\" created successfully.");
        }

        $this->closeModal();
    }

    public function toggleActive(int $planId): void
    {
        $plan = Plan::findOrFail($planId);
        $plan->update(['is_active' => !$plan->is_active]);
        $status = $plan->is_active ? 'activated' : 'deactivated';
        session()->flash('success', "Plan \"{$plan->name}\" has been {$status}.");
    }

    public function confirmDelete(int $planId): void
    {
        $this->confirmingDeleteId = $planId;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function deletePlan(): void
    {
        if (!$this->confirmingDeleteId) return;

        $plan = Plan::withCount('users')->findOrFail($this->confirmingDeleteId);

        if ($plan->users_count > 0) {
            session()->flash('error', "Cannot delete \"{$plan->name}\" because {$plan->users_count} user(s) are on this plan.");
            $this->confirmingDeleteId = null;
            return;
        }

        $planName = $plan->name;
        $plan->delete();
        session()->flash('success', "Plan \"{$planName}\" deleted successfully.");
        $this->confirmingDeleteId = null;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingPlanId = null;
        $this->name = '';
        $this->slug = '';
        $this->stripe_price_id = '';
        $this->price_monthly = '0.00';
        $this->price_yearly = '0.00';
        $this->max_sites = 1;
        $this->max_pages_per_site = 50;
        $this->max_ai_credits_monthly = 100;
        $this->features = '';
        $this->is_active = true;
        $this->sort_order = 0;
        $this->resetValidation();
    }

    public function render()
    {
        $plans = Plan::withCount('users')->orderBy('sort_order')->get();

        return view('livewire.admin.plan-manager', compact('plans'));
    }
}
