<?php

namespace App\Livewire\App\Monetization;

use App\Models\AffiliateLink;
use App\Models\Site;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class AffiliateLinkManager extends Component
{
    use WithPagination, WithFileUploads;

    public Site $site;

    public string $search = '';
    public string $originalUrl = '';
    public string $affiliateUrl = '';
    public string $keyword = '';

    public bool $showCreateForm = false;
    public bool $showImportModal = false;
    public $csvFile;

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function createLink(): void
    {
        $this->validate([
            'originalUrl' => 'required|url|max:1000',
            'affiliateUrl' => 'required|url|max:1000',
            'keyword' => 'nullable|string|max:255',
        ]);

        // Check for duplicate
        $exists = AffiliateLink::where('site_id', $this->site->id)
            ->where('original_url', $this->originalUrl)
            ->exists();

        if ($exists) {
            session()->flash('error', 'An affiliate link for this URL already exists.');
            return;
        }

        AffiliateLink::create([
            'tenant_id' => $this->site->tenant_id,
            'site_id' => $this->site->id,
            'original_url' => $this->originalUrl,
            'affiliate_url' => $this->affiliateUrl,
            'keyword' => $this->keyword ?: null,
            'clicks' => 0,
        ]);

        $this->reset(['originalUrl', 'affiliateUrl', 'keyword']);
        $this->showCreateForm = false;

        session()->flash('success', 'Affiliate link created successfully.');
    }

    public function deleteLink(int $id): void
    {
        $link = AffiliateLink::where('site_id', $this->site->id)->findOrFail($id);
        $link->delete();

        session()->flash('success', 'Affiliate link deleted.');
    }

    public function resetClicks(int $id): void
    {
        $link = AffiliateLink::where('site_id', $this->site->id)->findOrFail($id);
        $link->update(['clicks' => 0]);

        session()->flash('success', 'Click count reset.');
    }

    public function importCsv(): void
    {
        $this->validate([
            'csvFile' => 'required|file|mimes:csv,txt|max:1024',
        ]);

        $path = $this->csvFile->getRealPath();
        $rows = array_map('str_getcsv', file($path));

        // Remove header if present
        if (isset($rows[0]) && strtolower($rows[0][0] ?? '') === 'original_url') {
            array_shift($rows);
        }

        $imported = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            if (count($row) < 2) {
                $skipped++;
                continue;
            }

            $originalUrl = trim($row[0]);
            $affiliateUrl = trim($row[1]);
            $keyword = isset($row[2]) ? trim($row[2]) : null;

            if (!filter_var($originalUrl, FILTER_VALIDATE_URL) || !filter_var($affiliateUrl, FILTER_VALIDATE_URL)) {
                $skipped++;
                continue;
            }

            $exists = AffiliateLink::where('site_id', $this->site->id)
                ->where('original_url', $originalUrl)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            AffiliateLink::create([
                'tenant_id' => $this->site->tenant_id,
                'site_id' => $this->site->id,
                'original_url' => $originalUrl,
                'affiliate_url' => $affiliateUrl,
                'keyword' => $keyword,
                'clicks' => 0,
            ]);

            $imported++;
        }

        $this->showImportModal = false;
        $this->csvFile = null;

        session()->flash('success', "Imported {$imported} affiliate link(s). Skipped {$skipped}.");
    }

    public function render()
    {
        $links = AffiliateLink::where('site_id', $this->site->id)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('original_url', 'like', "%{$this->search}%")
                        ->orWhere('affiliate_url', 'like', "%{$this->search}%")
                        ->orWhere('keyword', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->paginate(15);

        return view('livewire.app.monetization.affiliate-link-manager', compact('links'));
    }
}
