<?php

namespace App\Livewire\App\Data;

use App\Models\DataSource;
use App\Models\DataEntry;
use App\Models\Site;
use App\Enums\DataSourceType;
use Livewire\Component;
use Livewire\WithFileUploads;

class CsvImporter extends Component
{
    use WithFileUploads;

    public Site $site;
    public $csvFile;
    public $name = '';
    public $preview = [];
    public $headers = [];
    public $mappings = [];
    public $importing = false;
    public $importedCount = 0;
    public $totalRows = 0;
    public $showPreview = false;

    public function mount(Site $site)
    {
        $this->site = $site;
    }

    public function updatedCsvFile()
    {
        $this->validate(['csvFile' => 'required|file|mimes:csv,txt|max:10240']);

        $path = $this->csvFile->getRealPath();
        $handle = fopen($path, 'r');
        $this->headers = fgetcsv($handle);
        $this->preview = [];
        $count = 0;

        while (($row = fgetcsv($handle)) !== false && $count < 5) {
            if (count($row) === count($this->headers)) {
                $this->preview[] = array_combine($this->headers, $row);
            }
            $count++;
        }

        // Count total rows
        $this->totalRows = 0;
        rewind($handle);
        fgetcsv($handle); // skip header
        while (fgetcsv($handle) !== false) {
            $this->totalRows++;
        }
        fclose($handle);

        $this->showPreview = true;
        $this->name = pathinfo($this->csvFile->getClientOriginalName(), PATHINFO_FILENAME);
    }

    public function import()
    {
        $this->validate([
            'csvFile' => 'required|file|mimes:csv,txt|max:10240',
            'name' => 'required|string|max:255',
        ]);

        $this->importing = true;

        $dataSource = DataSource::create([
            'tenant_id' => auth()->id(),
            'site_id' => $this->site->id,
            'name' => $this->name,
            'type' => DataSourceType::Csv,
            'config' => [
                'headers' => $this->headers,
                'original_filename' => $this->csvFile->getClientOriginalName(),
            ],
            'last_synced_at' => now(),
        ]);

        $path = $this->csvFile->getRealPath();
        $handle = fopen($path, 'r');
        $headers = fgetcsv($handle);
        $this->importedCount = 0;

        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) === count($headers)) {
                DataEntry::create([
                    'tenant_id' => auth()->id(),
                    'data_source_id' => $dataSource->id,
                    'data' => array_combine($headers, $row),
                    'checksum' => md5(implode('|', $row)),
                ]);
                $this->importedCount++;
            }
        }
        fclose($handle);

        $this->importing = false;
        session()->flash('message', "Successfully imported {$this->importedCount} entries!");
        $this->dispatch('data-imported');
    }

    public function removeFile()
    {
        $this->csvFile = null;
        $this->preview = [];
        $this->headers = [];
        $this->showPreview = false;
        $this->totalRows = 0;
        $this->name = '';
        $this->importedCount = 0;
    }

    public function render()
    {
        return view('livewire.app.data.csv-importer');
    }
}
