<?php

namespace App\Livewire\App\Monetization;

use App\Models\AdPlacement;
use App\Models\Site;
use Livewire\Component;
use Livewire\WithPagination;

class AdPlacementManager extends Component
{
    use WithPagination;

    public Site $site;

    public string $name = '';
    public string $type = 'adsense';
    public string $code = '';
    public string $position = 'in-content';
    public bool $isActive = true;

    public ?int $editingId = null;
    public string $editName = '';
    public string $editType = 'adsense';
    public string $editCode = '';
    public string $editPosition = 'in-content';
    public bool $editIsActive = true;

    public bool $showCreateModal = false;
    public bool $showEditModal = false;

    public function createPlacement(): void
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:adsense,custom,affiliate',
            'code' => 'required|string|max:10000',
            'position' => 'required|in:header,sidebar,in-content,footer,after-paragraph-2,before-content,after-content',
            'isActive' => 'boolean',
        ]);

        AdPlacement::create([
            'tenant_id' => $this->site->tenant_id,
            'site_id' => $this->site->id,
            'name' => $this->name,
            'type' => $this->type,
            'code' => $this->code,
            'position' => $this->position,
            'is_active' => $this->isActive,
        ]);

        $this->reset(['name', 'type', 'code', 'position', 'isActive']);
        $this->type = 'adsense';
        $this->position = 'in-content';
        $this->isActive = true;
        $this->showCreateModal = false;

        session()->flash('success', 'Ad placement created successfully.');
    }

    public function editPlacement(int $id): void
    {
        $placement = AdPlacement::where('site_id', $this->site->id)->findOrFail($id);

        $this->editingId = $id;
        $this->editName = $placement->name;
        $this->editType = $placement->type;
        $this->editCode = $placement->code;
        $this->editPosition = $placement->position;
        $this->editIsActive = $placement->is_active;
        $this->showEditModal = true;
    }

    public function updatePlacement(): void
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editType' => 'required|in:adsense,custom,affiliate',
            'editCode' => 'required|string|max:10000',
            'editPosition' => 'required|in:header,sidebar,in-content,footer,after-paragraph-2,before-content,after-content',
            'editIsActive' => 'boolean',
        ]);

        $placement = AdPlacement::where('site_id', $this->site->id)->findOrFail($this->editingId);

        $placement->update([
            'name' => $this->editName,
            'type' => $this->editType,
            'code' => $this->editCode,
            'position' => $this->editPosition,
            'is_active' => $this->editIsActive,
        ]);

        $this->showEditModal = false;
        $this->editingId = null;

        session()->flash('success', 'Ad placement updated successfully.');
    }

    public function toggleActive(int $id): void
    {
        $placement = AdPlacement::where('site_id', $this->site->id)->findOrFail($id);
        $placement->update(['is_active' => !$placement->is_active]);
    }

    public function deletePlacement(int $id): void
    {
        $placement = AdPlacement::where('site_id', $this->site->id)->findOrFail($id);
        $placement->delete();

        session()->flash('success', 'Ad placement deleted.');
    }

    public function render()
    {
        $placements = AdPlacement::where('site_id', $this->site->id)
            ->latest()
            ->paginate(12);

        return view('livewire.app.monetization.ad-placement-manager', compact('placements'));
    }
}
