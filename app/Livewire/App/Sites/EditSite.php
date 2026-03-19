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

    // Image generation settings
    public string $image_provider = 'pollinations';
    public string $image_api_key = '';
    public string $image_model = 'dall-e-3';
    public string $image_style = 'natural';

    public function mount(Site $site): void
    {
        $this->site = $site;
        $this->name = $site->name;
        $this->niche_type = $site->niche_type->value;
        $this->domain = $site->domain ?? '';

        $settings = $site->settings ?? [];
        $this->image_provider = $settings['image_provider'] ?? 'pollinations';
        $this->image_api_key = $settings['image_api_key'] ?? '';
        $this->image_model = $settings['image_model'] ?? 'dall-e-3';
        $this->image_style = $settings['image_style'] ?? 'natural';
    }

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'niche_type' => 'required|in:city,comparison,directory,custom',
            'domain' => 'nullable|string|max:255',
            'image_provider' => 'required|in:pollinations,openai',
            'image_api_key' => 'nullable|string|max:500',
            'image_model' => 'required|in:dall-e-3,dall-e-2',
            'image_style' => 'required|in:vivid,natural',
        ];
    }

    public function save()
    {
        $this->validate();

        $settings = $this->site->settings ?? [];
        $settings['image_provider'] = $this->image_provider;
        $settings['image_api_key'] = $this->image_api_key;
        $settings['image_model'] = $this->image_model;
        $settings['image_style'] = $this->image_style;

        $this->site->update([
            'name' => $this->name,
            'niche_type' => $this->niche_type,
            'domain' => $this->domain ?: null,
            'settings' => $settings,
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
