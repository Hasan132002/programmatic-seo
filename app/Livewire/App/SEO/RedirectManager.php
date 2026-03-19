<?php

namespace App\Livewire\App\SEO;

use App\Models\Redirect;
use App\Models\Site;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class RedirectManager extends Component
{
    use WithPagination, WithFileUploads;

    public Site $site;

    public string $search = '';
    public string $fromPath = '';
    public string $toPath = '';
    public int $statusCode = 301;

    public ?int $editingId = null;
    public string $editFromPath = '';
    public string $editToPath = '';
    public int $editStatusCode = 301;

    public bool $showCreateForm = false;
    public bool $showImportModal = false;
    public $csvFile;

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function createRedirect(): void
    {
        $this->validate([
            'fromPath' => 'required|string|max:500',
            'toPath' => 'required|string|max:500',
            'statusCode' => 'required|in:301,302',
        ]);

        $fromPath = '/' . ltrim($this->fromPath, '/');
        $toPath = str_starts_with($this->toPath, 'http') ? $this->toPath : '/' . ltrim($this->toPath, '/');

        // Check for duplicate
        $exists = Redirect::where('site_id', $this->site->id)
            ->where('from_path', $fromPath)
            ->exists();

        if ($exists) {
            session()->flash('error', 'A redirect from this path already exists.');
            return;
        }

        Redirect::create([
            'site_id' => $this->site->id,
            'from_path' => $fromPath,
            'to_path' => $toPath,
            'status_code' => $this->statusCode,
        ]);

        $this->reset(['fromPath', 'toPath', 'statusCode']);
        $this->statusCode = 301;
        $this->showCreateForm = false;

        session()->flash('success', 'Redirect created successfully.');
    }

    public function editRedirect(int $id): void
    {
        $redirect = Redirect::where('site_id', $this->site->id)->findOrFail($id);

        $this->editingId = $id;
        $this->editFromPath = $redirect->from_path;
        $this->editToPath = $redirect->to_path;
        $this->editStatusCode = $redirect->status_code;
    }

    public function updateRedirect(): void
    {
        $this->validate([
            'editFromPath' => 'required|string|max:500',
            'editToPath' => 'required|string|max:500',
            'editStatusCode' => 'required|in:301,302',
        ]);

        $redirect = Redirect::where('site_id', $this->site->id)->findOrFail($this->editingId);

        $fromPath = '/' . ltrim($this->editFromPath, '/');
        $toPath = str_starts_with($this->editToPath, 'http') ? $this->editToPath : '/' . ltrim($this->editToPath, '/');

        $redirect->update([
            'from_path' => $fromPath,
            'to_path' => $toPath,
            'status_code' => $this->editStatusCode,
        ]);

        $this->editingId = null;

        session()->flash('success', 'Redirect updated successfully.');
    }

    public function cancelEdit(): void
    {
        $this->editingId = null;
    }

    public function deleteRedirect(int $id): void
    {
        $redirect = Redirect::where('site_id', $this->site->id)->findOrFail($id);
        $redirect->delete();

        session()->flash('success', 'Redirect deleted.');
    }

    public function importCsv(): void
    {
        $this->validate([
            'csvFile' => 'required|file|mimes:csv,txt|max:1024',
        ]);

        $path = $this->csvFile->getRealPath();
        $rows = array_map('str_getcsv', file($path));

        // Remove header if present
        if (isset($rows[0]) && strtolower($rows[0][0] ?? '') === 'from') {
            array_shift($rows);
        }

        $imported = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            if (count($row) < 2) {
                $skipped++;
                continue;
            }

            $fromPath = '/' . ltrim(trim($row[0]), '/');
            $toPath = trim($row[1]);
            $statusCode = isset($row[2]) ? (int) trim($row[2]) : 301;

            if (!in_array($statusCode, [301, 302])) {
                $statusCode = 301;
            }

            if (!str_starts_with($toPath, 'http')) {
                $toPath = '/' . ltrim($toPath, '/');
            }

            $exists = Redirect::where('site_id', $this->site->id)
                ->where('from_path', $fromPath)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            Redirect::create([
                'site_id' => $this->site->id,
                'from_path' => $fromPath,
                'to_path' => $toPath,
                'status_code' => $statusCode,
            ]);

            $imported++;
        }

        $this->showImportModal = false;
        $this->csvFile = null;

        session()->flash('success', "Imported {$imported} redirect(s). Skipped {$skipped}.");
    }

    public function render()
    {
        $redirects = Redirect::where('site_id', $this->site->id)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('from_path', 'like', "%{$this->search}%")
                        ->orWhere('to_path', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->paginate(15);

        return view('livewire.app.seo.redirect-manager', compact('redirects'));
    }
}
