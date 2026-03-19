<?php

namespace App\Livewire\App\Pages;

use App\Enums\ContentStatus;
use App\Models\Page;
use App\Models\Site;
use Illuminate\Support\Str;
use Livewire\Component;

class PageEditor extends Component
{
    public Site $site;
    public ?Page $page = null;

    public string $title = '';
    public string $slug = '';
    public string $content_html = '';
    public string $meta_title = '';
    public string $meta_description = '';
    public string $status = 'draft';

    public bool $isEdit = false;

    public function mount(Site $site, ?Page $page = null): void
    {
        $this->site = $site;

        if ($page?->exists) {
            $this->page = $page;
            $this->isEdit = true;
            $this->title = $page->title ?? '';
            $this->slug = $page->slug ?? '';
            $this->content_html = $page->content_html ?? '';
            $this->meta_title = $page->meta_title ?? '';
            $this->meta_description = $page->meta_description ?? '';
            $this->status = $page->status?->value ?? 'draft';
        }
    }

    public function updatedTitle(): void
    {
        if (!$this->isEdit) {
            $this->slug = Str::slug($this->title);
        }
    }

    protected function rules(): array
    {
        $uniqueRule = $this->isEdit
            ? "unique:pages,slug,{$this->page->id},id,site_id,{$this->site->id}"
            : "unique:pages,slug,NULL,id,site_id,{$this->site->id}";

        return [
            'title' => 'required|string|max:500',
            'slug' => ['required', 'string', 'max:500', $uniqueRule],
            'content_html' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published',
        ];
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'slug' => Str::slug($this->slug),
            'content_html' => $this->content_html,
            'meta_title' => $this->meta_title ?: $this->title,
            'meta_description' => $this->meta_description ?: Str::limit(strip_tags($this->content_html), 160),
            'status' => $this->status,
            'generation_method' => 'manual',
            'published_at' => $this->status === 'published' ? now() : null,
        ];

        if ($this->isEdit) {
            $this->page->update($data);
            session()->flash('success', 'Page updated successfully.');
        } else {
            $data['site_id'] = $this->site->id;
            $page = Page::create($data);
            return redirect()->route('app.sites.pages.edit', [$this->site, $page])
                ->with('success', 'Page created successfully!');
        }
    }

    public function render()
    {
        return view('livewire.app.pages.page-editor');
    }
}
