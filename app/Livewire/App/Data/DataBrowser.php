<?php

namespace App\Livewire\App\Data;

use App\Models\DataEntry;
use App\Models\DataSource;
use Livewire\Component;
use Livewire\WithPagination;

class DataBrowser extends Component
{
    use WithPagination;

    public DataSource $dataSource;
    public string $search = '';
    public array $columns = [];

    public function mount(DataSource $dataSource)
    {
        $this->dataSource = $dataSource;

        // Determine columns from config headers or first entry
        if (isset($dataSource->config['headers'])) {
            $this->columns = $dataSource->config['headers'];
        } else {
            $firstEntry = $dataSource->entries()->first();
            if ($firstEntry && is_array($firstEntry->data)) {
                $this->columns = array_keys($firstEntry->data);
            }
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function delete(int $id): void
    {
        $entry = DataEntry::where('data_source_id', $this->dataSource->id)
            ->where('tenant_id', auth()->id())
            ->findOrFail($id);

        $entry->delete();
        session()->flash('success', 'Entry deleted successfully.');
    }

    public function deleteAll(): void
    {
        DataEntry::where('data_source_id', $this->dataSource->id)
            ->where('tenant_id', auth()->id())
            ->delete();

        session()->flash('success', 'All entries have been deleted.');
    }

    public function render()
    {
        $entries = DataEntry::where('data_source_id', $this->dataSource->id)
            ->where('tenant_id', auth()->id())
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    // Search across JSON data field
                    foreach ($this->columns as $column) {
                        $q->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(data, ?)) LIKE ?", [
                            '$."' . $column . '"',
                            '%' . $this->search . '%',
                        ]);
                    }
                });
            })
            ->latest()
            ->paginate(25);

        return view('livewire.app.data.data-browser', compact('entries'));
    }
}
