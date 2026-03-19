<?php

namespace App\Livewire\App\SEO;

use App\Models\InternalLink;
use App\Models\Page;
use App\Models\Site;
use Livewire\Component;
use Livewire\WithPagination;

class InternalLinkManager extends Component
{
    use WithPagination;

    public Site $site;

    public string $search = '';
    public ?int $sourcePageId = null;
    public ?int $targetPageId = null;
    public string $anchorText = '';
    public string $linkType = 'related';

    public bool $showCreateForm = false;

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function createLink(): void
    {
        $this->validate([
            'sourcePageId' => 'required|exists:pages,id',
            'targetPageId' => 'required|exists:pages,id|different:sourcePageId',
            'anchorText' => 'required|string|max:255',
            'linkType' => 'required|in:related,contextual,navigation',
        ]);

        // Verify both pages belong to this site
        $sourceExists = Page::where('id', $this->sourcePageId)->where('site_id', $this->site->id)->exists();
        $targetExists = Page::where('id', $this->targetPageId)->where('site_id', $this->site->id)->exists();

        if (!$sourceExists || !$targetExists) {
            session()->flash('error', 'Both pages must belong to this site.');
            return;
        }

        // Check for duplicate
        $exists = InternalLink::where('site_id', $this->site->id)
            ->where('source_page_id', $this->sourcePageId)
            ->where('target_page_id', $this->targetPageId)
            ->exists();

        if ($exists) {
            session()->flash('error', 'This link already exists.');
            return;
        }

        InternalLink::create([
            'site_id' => $this->site->id,
            'source_page_id' => $this->sourcePageId,
            'target_page_id' => $this->targetPageId,
            'anchor_text' => $this->anchorText,
            'link_type' => $this->linkType,
        ]);

        $this->reset(['sourcePageId', 'targetPageId', 'anchorText', 'linkType']);
        $this->linkType = 'related';
        $this->showCreateForm = false;

        session()->flash('success', 'Internal link created successfully.');
    }

    public function deleteLink(int $id): void
    {
        $link = InternalLink::where('site_id', $this->site->id)->findOrFail($id);
        $link->delete();

        session()->flash('success', 'Internal link deleted.');
    }

    public function autoGenerate(): void
    {
        $pages = $this->site->pages()->select('id', 'title', 'slug')->get();
        $created = 0;

        foreach ($pages as $sourcePage) {
            $sourceWords = collect(explode(' ', strtolower($sourcePage->title)))
                ->filter(fn ($w) => strlen($w) > 3)
                ->values();

            foreach ($pages as $targetPage) {
                if ($sourcePage->id === $targetPage->id) {
                    continue;
                }

                $targetWords = collect(explode(' ', strtolower($targetPage->title)))
                    ->filter(fn ($w) => strlen($w) > 3)
                    ->values();

                $commonWords = $sourceWords->intersect($targetWords);

                if ($commonWords->count() >= 2) {
                    $exists = InternalLink::where('site_id', $this->site->id)
                        ->where('source_page_id', $sourcePage->id)
                        ->where('target_page_id', $targetPage->id)
                        ->exists();

                    if (!$exists) {
                        InternalLink::create([
                            'site_id' => $this->site->id,
                            'source_page_id' => $sourcePage->id,
                            'target_page_id' => $targetPage->id,
                            'anchor_text' => $targetPage->title,
                            'link_type' => 'related',
                        ]);
                        $created++;
                    }
                }
            }
        }

        session()->flash('success', "Auto-generated {$created} internal link(s) based on title similarity.");
    }

    public function render()
    {
        $links = InternalLink::with(['sourcePage', 'targetPage'])
            ->where('site_id', $this->site->id)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('anchor_text', 'like', "%{$this->search}%")
                        ->orWhereHas('sourcePage', fn ($p) => $p->where('title', 'like', "%{$this->search}%"))
                        ->orWhereHas('targetPage', fn ($p) => $p->where('title', 'like', "%{$this->search}%"));
                });
            })
            ->latest()
            ->paginate(15);

        $pages = $this->site->pages()->select('id', 'title', 'slug')->orderBy('title')->get();

        return view('livewire.app.seo.internal-link-manager', compact('links', 'pages'));
    }
}
