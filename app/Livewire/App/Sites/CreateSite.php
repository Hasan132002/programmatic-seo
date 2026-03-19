<?php

namespace App\Livewire\App\Sites;

use App\Enums\NicheType;
use App\Models\Site;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateSite extends Component
{
    public string $name = '';
    public string $niche_type = 'custom';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'niche_type' => 'required|in:city,comparison,directory,custom',
        ];
    }

    public function save()
    {
        $this->validate();

        if (!auth()->user()->canCreateSite()) {
            session()->flash('error', 'Site limit reached. Upgrade your plan.');
            return;
        }

        $slug = Str::slug($this->name);
        $subdomain = $slug;

        $counter = 1;
        while (Site::withoutGlobalScopes()->where('subdomain', $subdomain)->exists()) {
            $subdomain = $slug . '-' . $counter++;
        }

        $site = Site::create([
            'name' => $this->name,
            'slug' => $slug,
            'subdomain' => $subdomain,
            'niche_type' => $this->niche_type,
            'seo_defaults' => [
                'title_template' => '{{title}} | ' . $this->name,
                'description_template' => '{{description}}',
            ],
        ]);

        return redirect()->route('app.sites.show', $site)
            ->with('success', 'Site created successfully!');
    }

    public function render()
    {
        return view('livewire.app.sites.create-site', [
            'nicheTypes' => NicheType::cases(),
        ]);
    }
}
