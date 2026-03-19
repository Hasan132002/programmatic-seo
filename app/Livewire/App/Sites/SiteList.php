<?php

namespace App\Livewire\App\Sites;

use App\Models\Site;
use Livewire\Component;

class SiteList extends Component
{
    public function delete(int $id): void
    {
        $site = Site::findOrFail($id);
        $site->delete();
        session()->flash('success', 'Site deleted successfully.');
    }

    public function togglePublish(int $id): void
    {
        $site = Site::findOrFail($id);
        $site->update(['is_published' => !$site->is_published]);
    }

    public function render()
    {
        $sites = Site::withCount('pages')->latest()->get();

        return view('livewire.app.sites.site-list', compact('sites'));
    }
}
