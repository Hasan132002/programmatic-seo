<?php

namespace App\Livewire\App\SEO;

use App\Models\Site;
use Livewire\Component;
use Livewire\WithFileUploads;

class SeoSettings extends Component
{
    use WithFileUploads;

    public Site $site;

    public string $defaultMetaTitle = '';
    public string $defaultMetaDescription = '';
    public string $robotsTxt = '';
    public string $googleAnalyticsId = '';
    public string $googleSearchConsoleId = '';
    public $ogImage;
    public ?string $existingOgImage = null;
    public string $twitterHandle = '';
    public string $canonicalBase = '';

    public bool $saved = false;

    public function mount(Site $site): void
    {
        $this->site = $site;

        $defaults = $site->seo_defaults ?? [];

        $this->defaultMetaTitle = $defaults['default_meta_title'] ?? '{page_title} | {site_name}';
        $this->defaultMetaDescription = $defaults['default_meta_description'] ?? '';
        $this->robotsTxt = $defaults['robots_txt'] ?? $this->getDefaultRobotsTxt();
        $this->googleAnalyticsId = $defaults['google_analytics_id'] ?? '';
        $this->googleSearchConsoleId = $defaults['google_search_console_id'] ?? '';
        $this->existingOgImage = $defaults['og_image'] ?? null;
        $this->twitterHandle = $defaults['twitter_handle'] ?? '';
        $this->canonicalBase = $defaults['canonical_base'] ?? $site->url;
    }

    public function save(): void
    {
        $this->validate([
            'defaultMetaTitle' => 'required|string|max:255',
            'defaultMetaDescription' => 'nullable|string|max:500',
            'robotsTxt' => 'nullable|string|max:5000',
            'googleAnalyticsId' => 'nullable|string|max:50',
            'googleSearchConsoleId' => 'nullable|string|max:100',
            'ogImage' => 'nullable|image|max:2048',
            'twitterHandle' => 'nullable|string|max:50',
            'canonicalBase' => 'nullable|url|max:255',
        ]);

        $ogImagePath = $this->existingOgImage;
        if ($this->ogImage) {
            $ogImagePath = $this->ogImage->store('sites/' . $this->site->id . '/seo', 'public');
        }

        $this->site->update([
            'seo_defaults' => [
                'default_meta_title' => $this->defaultMetaTitle,
                'default_meta_description' => $this->defaultMetaDescription,
                'robots_txt' => $this->robotsTxt,
                'google_analytics_id' => $this->googleAnalyticsId,
                'google_search_console_id' => $this->googleSearchConsoleId,
                'og_image' => $ogImagePath,
                'twitter_handle' => $this->twitterHandle,
                'canonical_base' => $this->canonicalBase,
            ],
        ]);

        $this->existingOgImage = $ogImagePath;
        $this->ogImage = null;
        $this->saved = true;
    }

    public function resetRobotsTxt(): void
    {
        $this->robotsTxt = $this->getDefaultRobotsTxt();
    }

    public function getRobotsTxtPreviewProperty(): string
    {
        $text = $this->robotsTxt;
        $text = str_replace('{site_url}', $this->site->url, $text);

        return $text;
    }

    protected function getDefaultRobotsTxt(): string
    {
        $url = $this->site->url;

        return <<<TXT
User-agent: *
Allow: /

Sitemap: {$url}/sitemap.xml
TXT;
    }

    public function render()
    {
        return view('livewire.app.seo.seo-settings');
    }
}
