<?php

namespace App\Livewire\App\Builder;

use App\Enums\ContentStatus;
use App\Enums\GenerationMethod;
use App\Models\BuilderBlock;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Support\Str;
use Livewire\Component;

class PageBuilderWrapper extends Component
{
    public Site $site;
    public ?Page $page = null;
    public string $title = '';
    public string $slug = '';
    public string $htmlContent = '';
    public string $cssContent = '';
    public string $jsonContent = '';
    public string $metaTitle = '';
    public string $metaDescription = '';
    public string $status = 'draft';
    public string $activePanel = 'blocks';
    public bool $isEdit = false;

    public function mount(Site $site, ?Page $page = null): void
    {
        $this->site = $site;

        if ($page?->exists) {
            $this->page = $page;
            $this->isEdit = true;
            $this->title = $page->title ?? '';
            $this->slug = $page->slug ?? '';
            $this->htmlContent = $page->content_html ?? '';
            $this->jsonContent = $page->content_json ? json_encode($page->content_json) : '';
            $this->metaTitle = $page->meta_title ?? '';
            $this->metaDescription = $page->meta_description ?? '';
            $this->status = $page->status?->value ?? 'draft';
        }
    }

    public function updatedTitle($value): void
    {
        if (! $this->isEdit) {
            $this->slug = Str::slug($value);
        }
    }

    public function saveFromBuilder($html, $css, $json): void
    {
        $this->htmlContent = $html;
        $this->cssContent = $css;
        $this->jsonContent = $json;
        $this->save();
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:500',
            'slug' => 'required|string|max:500',
        ]);

        $fullHtml = $this->htmlContent;
        if ($this->cssContent) {
            $fullHtml = '<style>' . $this->cssContent . '</style>' . $fullHtml;
        }

        $data = [
            'title' => $this->title,
            'slug' => Str::slug($this->slug),
            'content_html' => $fullHtml,
            'content_json' => $this->jsonContent ? json_decode($this->jsonContent, true) : null,
            'meta_title' => $this->metaTitle ?: $this->title,
            'meta_description' => $this->metaDescription ?: Str::limit(strip_tags($this->htmlContent), 160),
            'status' => ContentStatus::from($this->status),
            'generation_method' => GenerationMethod::Manual,
            'published_at' => $this->status === 'published' ? now() : null,
        ];

        if ($this->isEdit && $this->page) {
            $this->page->update($data);
            session()->flash('success', 'Page updated successfully!');
        } else {
            $data['site_id'] = $this->site->id;
            $this->page = Page::create($data);
            $this->isEdit = true;
            session()->flash('success', 'Page created successfully!');
        }

        $this->redirect(route('app.sites.pages.index', $this->site), navigate: true);
    }

    public function render()
    {
        $blocks = BuilderBlock::where('is_system', true)
            ->orWhere(function ($query) {
                if (auth()->check()) {
                    $query->where('tenant_id', auth()->id());
                }
            })
            ->get();

        return view('livewire.app.builder.page-builder-wrapper', [
            'blocks' => $blocks,
        ]);
    }
}
