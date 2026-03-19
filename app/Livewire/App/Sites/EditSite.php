<?php

namespace App\Livewire\App\Sites;

use App\Enums\NicheType;
use App\Models\Site;
use Livewire\Component;

class EditSite extends Component
{
    public Site $site;
    public string $name = '';
    public string $niche_type = '';
    public string $domain = '';

    public function mount(Site $site): void
    {
        $this->site = $site;
        $this->name = $site->name;
        $this->niche_type = $site->niche_type->value;
        $this->domain = $site->domain ?? '';
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'niche_type' => 'required|in:city,comparison,directory,custom',
            'domain' => 'nullable|string|max:255',
        ];
    }

    public function save()
    {
        $this->validate();

        $this->site->update([
            'name' => $this->name,
            'niche_type' => $this->niche_type,
            'domain' => $this->domain ?: null,
        ]);

        session()->flash('success', 'Site updated successfully.');
    }

    public function render()
    {
        return view('livewire.app.sites.edit-site', [
            'nicheTypes' => NicheType::cases(),
        ]);
    }
}
