<?php

namespace App\Livewire\App\Data;

use App\Models\DataEntry;
use App\Models\DataSource;
use App\Models\Site;
use App\Enums\DataSourceType;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class DataSourceManager extends Component
{
    public Site $site;
    public string $search = '';

    // Create manual data source
    public bool $showCreateModal = false;
    public string $newName = '';
    public string $newType = 'manual';

    // API config
    public string $apiUrl = '';
    public string $apiMethod = 'GET';
    public string $apiDataPath = '';

    protected $listeners = ['data-imported' => '$refresh'];

    public function mount(Site $site)
    {
        $this->site = $site;
    }

    public function openCreate(): void
    {
        $this->reset(['newName', 'newType', 'apiUrl', 'apiMethod', 'apiDataPath']);
        $this->showCreateModal = true;
    }

    public function create(): void
    {
        $this->validate([
            'newName' => 'required|string|max:255',
            'newType' => 'required|in:manual,api',
        ]);

        $config = [];
        if ($this->newType === 'api') {
            $this->validate([
                'apiUrl' => 'required|url',
                'apiMethod' => 'required|in:GET,POST',
            ]);
            $config = [
                'url' => $this->apiUrl,
                'method' => $this->apiMethod,
                'data_path' => $this->apiDataPath ?: null,
            ];
        }

        DataSource::create([
            'tenant_id' => auth()->id(),
            'site_id' => $this->site->id,
            'name' => $this->newName,
            'type' => $this->newType,
            'config' => $config,
        ]);

        $this->showCreateModal = false;
        session()->flash('success', "Data source \"{$this->newName}\" has been created.");
    }

    public function delete(int $id): void
    {
        $dataSource = DataSource::where('site_id', $this->site->id)
            ->where('tenant_id', auth()->id())
            ->findOrFail($id);

        $dataSource->entries()->delete();
        $dataSource->delete();

        session()->flash('success', "Data source \"{$dataSource->name}\" and all its entries have been deleted.");
    }

    public function resync(int $id): void
    {
        $dataSource = DataSource::where('site_id', $this->site->id)
            ->where('tenant_id', auth()->id())
            ->findOrFail($id);

        if ($dataSource->type !== DataSourceType::Api) {
            session()->flash('error', 'Only API data sources can be re-synced.');
            return;
        }

        $config = $dataSource->config ?? [];
        $url = $config['url'] ?? '';

        if (empty($url)) {
            session()->flash('error', 'No API URL configured for this data source.');
            return;
        }

        try {
            $method = strtolower($config['method'] ?? 'GET');
            $response = Http::timeout(30)->{$method}($url);

            if (!$response->successful()) {
                session()->flash('error', "API returned status {$response->status()}.");
                return;
            }

            $data = $response->json();

            // Extract nested data using dot-notation path (e.g. "data.items")
            $dataPath = $config['data_path'] ?? null;
            if ($dataPath) {
                $data = data_get($data, $dataPath, []);
            }

            if (!is_array($data)) {
                session()->flash('error', 'API response is not a valid array.');
                return;
            }

            // If it's a list of objects, import each as a data entry
            $imported = 0;
            $rows = isset($data[0]) ? $data : [$data];

            foreach ($rows as $row) {
                if (!is_array($row)) {
                    continue;
                }

                DataEntry::create([
                    'data_source_id' => $dataSource->id,
                    'data' => $row,
                ]);
                $imported++;
            }

            $dataSource->update(['last_synced_at' => now()]);
            session()->flash('success', "Synced {$imported} entries from \"{$dataSource->name}\".");
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            session()->flash('error', 'Could not connect to the API endpoint. Please check the URL.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Sync failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $dataSources = DataSource::where('site_id', $this->site->id)
            ->where('tenant_id', auth()->id())
            ->withCount('entries')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->get();

        return view('livewire.app.data.data-source-manager', compact('dataSources'));
    }
}
