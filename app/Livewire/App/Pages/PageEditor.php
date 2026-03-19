<?php

namespace App\Livewire\App\Pages;

use App\Enums\ContentStatus;
use App\Enums\NicheType;
use App\Models\Page;
use App\Models\PageTemplate;
use App\Models\Site;
use App\Services\AI\OpenAIService;
use App\Services\Content\PromptBuilder;
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
    public ?int $template_id = null;

    public bool $isEdit = false;

    // AI Generation state
    public bool $aiGenerating = false;
    public bool $aiGeneratingMeta = false;
    public string $aiError = '';
    public string $aiCustomPrompt = '';
    public string $aiContentType = 'full'; // full, intro, faq, features, conclusion

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
            $this->template_id = $page->template_id;
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
            'template_id' => 'nullable|exists:page_templates,id',
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
            'template_id' => $this->template_id,
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

    public function generateContent(): void
    {
        if (empty($this->title)) {
            $this->aiError = 'Please enter a page title first.';
            return;
        }

        $this->aiGenerating = true;
        $this->aiError = '';

        try {
            $settings = $this->site->settings ?? [];
            $siteApiKey = $settings['openai_api_key'] ?? null;
            $siteModel = $settings['ai_model'] ?? null;
            $siteTone = $settings['ai_tone'] ?? 'professional';

            if ($siteApiKey) {
                config(['openai.api_key' => $siteApiKey]);
            }

            $ai = app(OpenAIService::class);
            $promptBuilder = app(PromptBuilder::class);

            $options = [];
            if ($siteModel) {
                $options['model'] = $siteModel;
            }

            $variables = [
                'title' => $this->title,
                'slug' => $this->slug,
            ];

            if ($this->aiContentType === 'full') {
                $prompt = $promptBuilder->build($this->site->niche_type, $variables);
                $prompt .= "\n\nTONE: Write in a {$siteTone} tone.";

                if ($this->aiCustomPrompt) {
                    $prompt .= "\n\nADDITIONAL INSTRUCTIONS:\n{$this->aiCustomPrompt}";
                }

                $response = $ai->generate($prompt, $options);
                $this->content_html = $response->content;
            } else {
                $context = array_merge($variables, [
                    'niche_type' => $this->site->niche_type->value,
                ]);

                if ($this->aiCustomPrompt) {
                    $context['additional_instructions'] = $this->aiCustomPrompt;
                }

                $prompt = $promptBuilder->buildSection($this->aiContentType, $context);
                $response = $ai->generate($prompt, $options);

                // Append section to existing content
                $this->content_html = trim($this->content_html) . "\n\n" . $response->content;
            }

            $this->dispatch('content-updated', html: $this->content_html);
        } catch (\Throwable $e) {
            $this->aiError = 'AI generation failed: ' . $e->getMessage();
        } finally {
            $this->aiGenerating = false;
        }
    }

    public function generateMetaTags(): void
    {
        if (empty($this->title)) {
            $this->aiError = 'Please enter a page title first.';
            return;
        }

        $this->aiGeneratingMeta = true;
        $this->aiError = '';

        try {
            $ai = app(OpenAIService::class);

            $contentSnippet = Str::limit(strip_tags($this->content_html), 500);

            $prompt = <<<PROMPT
Generate SEO-optimized meta tags for this page.

Page Title: {$this->title}
Site Niche: {$this->site->niche_type->value}
Content Preview: {$contentSnippet}

Return ONLY a JSON object with these two keys:
- "meta_title": SEO-optimized title (max 60 chars, include primary keyword naturally)
- "meta_description": Compelling meta description (max 155 chars, include call-to-action)

Output only the JSON, no code fences or extra text.
PROMPT;

            $response = $ai->generate($prompt, ['max_tokens' => 200, 'temperature' => 0.5]);
            $json = json_decode($response->content, true);

            if ($json && isset($json['meta_title'])) {
                $this->meta_title = Str::limit($json['meta_title'], 60, '');
                $this->meta_description = Str::limit($json['meta_description'] ?? '', 155, '');
            } else {
                // Fallback: try to parse from plain text
                $this->meta_title = Str::limit($this->title, 60, '');
                $this->meta_description = Str::limit(strip_tags($this->content_html), 155, '');
                $this->aiError = 'Meta tags generated with fallback. You may want to refine them.';
            }
        } catch (\Throwable $e) {
            $this->aiError = 'Meta generation failed: ' . $e->getMessage();
        } finally {
            $this->aiGeneratingMeta = false;
        }
    }

    public function render()
    {
        $templates = PageTemplate::withoutGlobalScopes()
            ->where(function ($q) {
                $q->where('is_system', true)
                  ->orWhere('site_id', $this->site->id);
            })
            ->orderBy('is_system', 'desc')
            ->orderBy('name')
            ->get(['id', 'name', 'niche_type', 'is_system']);

        return view('livewire.app.pages.page-editor', compact('templates'));
    }
}
