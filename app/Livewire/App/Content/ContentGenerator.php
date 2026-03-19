<?php

namespace App\Livewire\App\Content;

use App\Enums\ContentStatus;
use App\Enums\GenerationMethod;
use App\Models\ContentGenerationJob;
use App\Models\Page;
use App\Models\PageTemplate;
use App\Models\Site;
use App\Services\AI\AIServiceInterface;
use Illuminate\Support\Str;
use Livewire\Component;

class ContentGenerator extends Component
{
    public Site $site;
    public string $generationMethod = 'ai';
    public $templateId = null;
    public string $prompt = '';
    public string $customInstructions = '';
    public string $tone = 'professional';
    public int $wordCount = 1000;
    public string $keywords = '';
    public array $selectedDataEntries = [];
    public bool $generating = false;
    public ?string $batchId = null;
    public int $progress = 0;

    // Single page generation
    public string $pageTitle = '';
    public string $pageSlug = '';

    // Bulk mode
    public bool $bulkMode = false;
    public int $bulkCount = 0;

    protected function rules(): array
    {
        return [
            'pageTitle' => 'required_if:bulkMode,false|string|max:255',
            'pageSlug' => 'nullable|string|max:255',
            'prompt' => 'required|string|min:10',
            'generationMethod' => 'required|in:ai,template,hybrid,manual',
            'tone' => 'required|in:professional,casual,academic,friendly,persuasive',
            'wordCount' => 'required|integer|min:300|max:5000',
            'keywords' => 'nullable|string|max:1000',
            'customInstructions' => 'nullable|string|max:2000',
            'templateId' => 'nullable|exists:page_templates,id',
        ];
    }

    protected $messages = [
        'pageTitle.required_if' => 'Page title is required for single page generation.',
        'prompt.required' => 'A content prompt is required.',
        'prompt.min' => 'The prompt must be at least 10 characters.',
    ];

    public function mount(Site $site): void
    {
        $this->site = $site;
    }

    public function updatedPageTitle(string $value): void
    {
        $this->pageSlug = Str::slug($value);
    }

    public function updatedGenerationMethod(string $value): void
    {
        if ($value === 'manual') {
            return;
        }

        // Reset template selection when switching away from template/hybrid
        if (!in_array($value, ['template', 'hybrid'])) {
            $this->templateId = null;
        }
    }

    public function generate(): void
    {
        $this->validate();
        $this->generating = true;

        if ($this->bulkMode) {
            $this->generateBulk();
        } else {
            $this->generateSingle();
        }
    }

    protected function generateSingle(): void
    {
        $batchId = Str::uuid()->toString();

        $job = ContentGenerationJob::create([
            'tenant_id' => auth()->id(),
            'site_id' => $this->site->id,
            'batch_id' => $batchId,
            'provider' => 'openai',
            'prompt_template' => $this->prompt,
            'input_data' => [
                'title' => $this->pageTitle,
                'slug' => $this->pageSlug,
                'tone' => $this->tone,
                'word_count' => $this->wordCount,
                'keywords' => $this->keywords,
                'custom_instructions' => $this->customInstructions,
                'generation_method' => $this->generationMethod,
                'template_id' => $this->templateId,
            ],
            'status' => 'pending',
            'attempts' => 0,
        ]);

        try {
            $aiService = app(AIServiceInterface::class);
            $builtPrompt = $this->buildPrompt();

            $response = $aiService->generate($builtPrompt, [
                'max_tokens' => (int) ($this->wordCount * 1.5),
            ]);

            $slug = $this->pageSlug ?: Str::slug($this->pageTitle);

            // Ensure unique slug within the site
            $slugBase = $slug;
            $counter = 1;
            while (Page::withoutGlobalScopes()->where('site_id', $this->site->id)->where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $counter;
                $counter++;
            }

            $page = Page::create([
                'tenant_id' => auth()->id(),
                'site_id' => $this->site->id,
                'title' => $this->pageTitle,
                'slug' => $slug,
                'content_html' => $response->content,
                'meta_title' => Str::limit($this->pageTitle, 60),
                'meta_description' => Str::limit(strip_tags($response->content), 155),
                'generation_method' => GenerationMethod::from($this->generationMethod),
                'status' => ContentStatus::Draft,
                'template_id' => $this->templateId,
            ]);

            $job->update([
                'page_id' => $page->id,
                'output_content' => $response->content,
                'tokens_used' => $response->tokensUsed,
                'cost_cents' => $response->estimatedCostCents,
                'status' => 'completed',
            ]);

            $this->generating = false;
            session()->flash('success', 'Content generated successfully!');
            $this->redirect(route('app.sites.pages.edit', [$this->site, $page]), navigate: true);
        } catch (\Exception $e) {
            $job->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'attempts' => $job->attempts + 1,
            ]);
            $this->generating = false;
            session()->flash('error', 'Generation failed: ' . $e->getMessage());
        }
    }

    protected function buildPrompt(): string
    {
        $prompt = "Write a {$this->wordCount}-word article about: {$this->prompt}\n";
        $prompt .= "Title: {$this->pageTitle}\n";
        $prompt .= "Tone: {$this->tone}\n";

        if ($this->keywords) {
            $prompt .= "Target Keywords: {$this->keywords}\n";
        }

        if ($this->customInstructions) {
            $prompt .= "Additional Instructions: {$this->customInstructions}\n";
        }

        // If a template is selected, include its variable schema context
        if ($this->templateId) {
            $template = PageTemplate::find($this->templateId);
            if ($template && $template->variable_schema) {
                $prompt .= "Template Variables Schema: " . json_encode($template->variable_schema) . "\n";
            }
        }

        $prompt .= "\nReturn the content as clean, semantic HTML with proper headings (h2, h3), paragraphs, and lists where appropriate.";
        $prompt .= "\nDo NOT include the title as an h1 tag.";
        $prompt .= "\nDo NOT include <html>, <head>, <body>, or <doctype> tags.";
        $prompt .= "\nMake the content unique, engaging, and optimised for SEO.";

        return $prompt;
    }

    protected function generateBulk(): void
    {
        $this->generating = false;
        session()->flash('info', 'For bulk generation, please use the dedicated bulk generator tool.');
        $this->redirect(route('app.sites.content.bulk', $this->site), navigate: true);
    }

    public function resetForm(): void
    {
        $this->reset([
            'pageTitle', 'pageSlug', 'prompt', 'customInstructions',
            'tone', 'wordCount', 'keywords', 'templateId',
            'generating', 'bulkMode',
        ]);
        $this->tone = 'professional';
        $this->wordCount = 1000;
        $this->generationMethod = 'ai';
    }

    public function render()
    {
        return view('livewire.app.content.content-generator', [
            'templates' => PageTemplate::where(function ($q) {
                $q->where('is_system', true)->orWhere('tenant_id', auth()->id());
            })->get(),
            'recentJobs' => ContentGenerationJob::where('site_id', $this->site->id)
                ->latest()
                ->take(10)
                ->get(),
        ]);
    }
}
