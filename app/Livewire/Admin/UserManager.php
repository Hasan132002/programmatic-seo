<?php

namespace App\Livewire\Admin;

use App\Models\Plan;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public string $filterPlan = '';

    // For plan change modal
    public ?int $editingUserId = null;
    public string $selectedPlanId = '';

    // For delete confirmation
    public ?int $confirmingDeleteId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'filterPlan' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterPlan(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function toggleAdmin(int $userId): void
    {
        $user = User::findOrFail($userId);

        // Prevent self-demotion
        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot remove your own admin status.');
            return;
        }

        $user->update(['is_admin' => !$user->is_admin]);
        session()->flash('success', $user->is_admin ? "Made {$user->name} an admin." : "Removed admin from {$user->name}.");
    }

    public function openChangePlan(int $userId): void
    {
        $user = User::findOrFail($userId);
        $this->editingUserId = $userId;
        $this->selectedPlanId = (string) ($user->plan_id ?? '');
    }

    public function savePlan(): void
    {
        if (!$this->editingUserId) return;

        $user = User::findOrFail($this->editingUserId);
        $planId = $this->selectedPlanId !== '' ? (int) $this->selectedPlanId : null;
        $user->update(['plan_id' => $planId]);

        $planName = $planId ? Plan::find($planId)?->name : 'None';
        session()->flash('success', "Changed {$user->name}'s plan to {$planName}.");
        $this->closeChangePlan();
    }

    public function closeChangePlan(): void
    {
        $this->editingUserId = null;
        $this->selectedPlanId = '';
    }

    public function confirmDelete(int $userId): void
    {
        $this->confirmingDeleteId = $userId;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function deleteUser(): void
    {
        if (!$this->confirmingDeleteId) return;

        $user = User::findOrFail($this->confirmingDeleteId);

        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot delete your own account.');
            $this->confirmingDeleteId = null;
            return;
        }

        $userName = $user->name;
        $user->sites()->each(fn ($site) => $site->pages()->delete());
        $user->sites()->delete();
        $user->delete();

        session()->flash('success', "Deleted user {$userName} and all associated data.");
        $this->confirmingDeleteId = null;
    }

    public function render()
    {
        $users = User::query()
            ->with('plan')
            ->withCount(['sites', 'pages'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filterPlan, function ($query) {
                if ($this->filterPlan === 'none') {
                    $query->whereNull('plan_id');
                } else {
                    $query->where('plan_id', $this->filterPlan);
                }
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        $plans = Plan::orderBy('sort_order')->get();

        return view('livewire.admin.user-manager', compact('users', 'plans'));
    }
}
