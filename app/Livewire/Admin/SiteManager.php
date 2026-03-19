<?php

namespace App\Livewire\Admin;

use App\Enums\NicheType;
use App\Models\Site;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class SiteManager extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterUser = '';
    public string $filterNiche = '';
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public ?int $confirmingDeleteId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterUser' => ['except' => ''],
        'filterNiche' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterUser(): void
    {
        $this->resetPage();
    }

    public function updatingFilterNiche(): void
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

    public function togglePublish(int $siteId): void
    {
        $site = Site::withoutGlobalScopes()->findOrFail($siteId);
        $site->update(['is_published' => !$site->is_published]);
    }

    public function confirmDelete(int $siteId): void
    {
        $this->confirmingDeleteId = $siteId;
    }

    public function cancelDelete(): void
    {
        $this->confirmingDeleteId = null;
    }

    public function deleteSite(): void
    {
        if (!$this->confirmingDeleteId) return;

        $site = Site::withoutGlobalScopes()->findOrFail($this->confirmingDeleteId);
        $siteName = $site->name;

        // Cascade delete pages
        $site->pages()->delete();
        $site->delete();

        session()->flash('success', "Site \"{$siteName}\" and all its pages have been deleted.");
        $this->confirmingDeleteId = null;
    }

    public function render()
    {
        $sites = Site::withoutGlobalScopes()
            ->with('tenant')
            ->withCount('pages')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('domain', 'like', "%{$this->search}%")
                      ->orWhere('subdomain', 'like', "%{$this->search}%");
                });
            })
            ->when($this->filterUser, function ($query) {
                $query->where('tenant_id', $this->filterUser);
            })
            ->when($this->filterNiche, function ($query) {
                $query->where('niche_type', $this->filterNiche);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $nicheTypes = NicheType::cases();

        return view('livewire.admin.site-manager', compact('sites', 'users', 'nicheTypes'));
    }
}
