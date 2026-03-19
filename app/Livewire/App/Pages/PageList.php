<?php

namespace App\Livewire\App\Pages;

use App\Models\Page;
use App\Models\Site;
use Livewire\Component;
use Livewire\WithPagination;

class PageList extends Component
{
    use WithPagination;

    public Site $site;
    public string $search = '';
    public string $statusFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        Page::where('site_id', $this->site->id)->findOrFail($id)->delete();
        session()->flash('success', 'Page deleted.');
    }

    public function togglePublish(int $id): void
    {
        $page = Page::where('site_id', $this->site->id)->findOrFail($id);

        if ($page->status->value === 'published') {
            $page->update(['status' => 'draft', 'published_at' => null]);
        } else {
            $page->update(['status' => 'published', 'published_at' => now()]);
        }
    }

    public function render()
    {
        $pages = Page::where('site_id', $this->site->id)
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(20);

        return view('livewire.app.pages.page-list', compact('pages'));
    }
}
