<?php

namespace App\Livewire\App\Content;

use App\Enums\ContentStatus;
use App\Enums\GenerationMethod;
use App\Models\ContentGenerationJob;
use App\Models\DataEntry;
use App\Models\DataSource;
use App\Models\Page;
use App\Models\PageTemplate;
use App\Models\Site;
use App\Services\AI\AIServiceInterface;
use App\Services\Content\TemplateEngine;
use Illuminate\Support\Str;
use Livewire\Component;

class BulkPageGenerator extends Component
{
    public Site $site;
    public ?int $dataSourceId = null;
    public ?int $templateId = null;
    public array $columnMappings = [];
    public array $previewPages = [];
    public bool $generating = false;
    public int $progress = 0;
    public int $total = 0;
    public int $successCount = 0;
    public int $failCount = 0;

    // Wizard step
    public int $currentStep = 1;

    // Cached data for rendering
    public array $dataSourceColumns = [];
    public array $templateVariables = [];
    public ?string $batchId = null;

    // Title and slug mapping
    public string $titleColumn = '';
    public string $slugColumn = '';

    // Generation settings
    public string $generationMethod = 'hybrid';
    public string $tone = 'professional';
    public int $wordCount = 1000;
    public string $prompt = '';

    protected function rules(): array
    {
        return [
            'dataSourceId' => 'required|exists:data_sources,id',
            'templateId' => 'required|exists:page_templates,id',
            'titleColumn' => 'required|string',
            'columnMappings' => 'required|array|min:1',
        ];
    }

    protected $messages = [
        'dataSourceId.required' => 'Please select a data source.',
        'templateId.required' => 'Please select a template.',
        'titleColumn.required' => 'Please map a column for page titles.',
        'columnMappings.required' => 'Please map at least one template variable.',
    ];

    public function mount(Site $site): void
    {
        $this->site = $site;
    }

    /**
     * When data source changes, load its columns.
     */
    public function updatedDataSourceId($value): void
    {
        $this->dataSourceColumns = [];
        $this->columnMappings = [];
        $this->previewPages = [];

        if (!$value) {
            return;
        }

        $dataSource = DataSource::find($value);
        if (!$dataSource) {
            return;
        }

        $firstEntry = $dataSource->entries()->first();
        if ($firstEntry && is_array($firstEntry->data)) {
            $this->dataSourceColumns = array_keys($firstEntry->data);
        }

        $this->total = $dataSource->entries()->count();
    }

    /**
     * When template changes, load its variable schema.
     */
    public function updatedTemplateId($value): void
    {
        $this->templateVariables = [];
        $this->columnMappings = [];
        $this->previewPages = [];

        if (!$value) {
            return;
        }

        $template = PageTemplate::find($value);
        if (!$template) {
            return;
        }

        if ($template->variable_schema && is_array($template->variable_schema)) {
            $this->templateVariables = array_keys($template->variable_schema);
        } else {
            // Extract variables from layout_html
            $engine = app(TemplateEngine::class);
            $html = $template->layout_html ?? '';
            $this->templateVariables = $engine->extractVariables($html);
        }

        // Auto-map columns with matching names
        foreach ($this->templateVariables as $variable) {
            if (in_array($variable, $this->dataSourceColumns)) {
                $this->columnMappings[$variable] = $variable;
            } else {
                $this->columnMappings[$variable] = '';
            }
        }
    }

    /**
     * Navigate to next step in the wizard.
     */
    public function nextStep(): void
    {
        if ($this->currentStep === 1 && !$this->dataSourceId) {
            $this->addError('dataSourceId', 'Please select a data source.');
            return;
        }

        if ($this->currentStep === 2 && !$this->templateId) {
            $this->addError('templateId', 'Please select a template.');
            return;
        }

        if ($this->currentStep === 3) {
            if (empty($this->titleColumn)) {
                $this->addError('titleColumn', 'Please map a column for page titles.');
                return;
            }
            // Generate preview when advancing to step 4
            $this->generatePreview();
        }

        $this->currentStep = min(4, $this->currentStep + 1);
    }

    /**
     * Navigate to previous step.
     */
    public function previousStep(): void
    {
        $this->currentStep = max(1, $this->currentStep - 1);
    }

    /**
     * Go to a specific step (only if it's been reached before).
     */
    public function goToStep(int $step): void
    {
        if ($step < $this->currentStep) {
            $this->currentStep = $step;
        }
    }

    /**
     * Generate preview pages from first 3 data entries.
     */
    public function generatePreview(): void
    {
        $this->previewPages = [];

        if (!$this->dataSourceId || !$this->templateId) {
            return;
        }

        $dataSource = DataSource::find($this->dataSourceId);
        $template = PageTemplate::find($this->templateId);

        if (!$dataSource || !$template) {
            return;
        }

        $entries = $dataSource->entries()->take(3)->get();
        $engine = app(TemplateEngine::class);

        foreach ($entries as $entry) {
            $mappedData = $this->mapEntryData($entry);
            $title = $mappedData[$this->titleColumn] ?? 'Untitled';
            $slug = Str::slug($this->slugColumn ? ($mappedData[$this->slugColumn] ?? $title) : $title);

            $renderedContent = '';
            if ($template->layout_html) {
                $renderedContent = $engine->render($template->layout_html, $mappedData);
            }

            $this->previewPages[] = [
                'title' => $title,
                'slug' => $slug,
                'content_preview' => Str::limit(strip_tags($renderedContent), 300),
                'variables' => $mappedData,
            ];
        }
    }

    /**
     * Map a data entry's columns to template variables using the column mapping.
     */
    protected function mapEntryData(DataEntry $entry): array
    {
        $mappedData = [];
        $entryData = $entry->data ?? [];

        foreach ($this->columnMappings as $templateVar => $sourceColumn) {
            if ($sourceColumn && isset($entryData[$sourceColumn])) {
                $mappedData[$templateVar] = $entryData[$sourceColumn];
            }
        }

        // Also include all raw data for title/slug mapping
        foreach ($entryData as $key => $value) {
            if (!isset($mappedData[$key])) {
                $mappedData[$key] = $value;
            }
        }

        return $mappedData;
    }

    /**
     * Generate all pages from data source entries.
     */
    public function generateAll(): void
    {
        if (!$this->dataSourceId || !$this->templateId || empty($this->titleColumn)) {
            session()->flash('error', 'Please complete all steps before generating.');
            return;
        }

        $this->generating = true;
        $this->progress = 0;
        $this->successCount = 0;
        $this->failCount = 0;
        $this->batchId = Str::uuid()->toString();

        $dataSource = DataSource::find($this->dataSourceId);
        $template = PageTemplate::find($this->templateId);

        if (!$dataSource || !$template) {
            $this->generating = false;
            session()->flash('error', 'Data source or template not found.');
            return;
        }

        $entries = $dataSource->entries()->get();
        $this->total = $entries->count();
        $engine = app(TemplateEngine::class);

        foreach ($entries as $index => $entry) {
            $this->progress = $index + 1;

            try {
                $mappedData = $this->mapEntryData($entry);
                $title = $mappedData[$this->titleColumn] ?? 'Untitled Page ' . ($index + 1);
                $slug = Str::slug($this->slugColumn ? ($mappedData[$this->slugColumn] ?? $title) : $title);

                // Ensure unique slug
                $slugBase = $slug;
                $counter = 1;
                while (Page::withoutGlobalScopes()->where('site_id', $this->site->id)->where('slug', $slug)->exists()) {
                    $slug = $slugBase . '-' . $counter;
                    $counter++;
                }

                // Render template content
                $content = '';
                if ($template->layout_html) {
                    $content = $engine->render($template->layout_html, $mappedData);
                }

                // For hybrid mode, enhance with AI
                if ($this->generationMethod === 'hybrid' || $this->generationMethod === 'ai') {
                    $job = ContentGenerationJob::create([
                        'tenant_id' => auth()->id(),
                        'site_id' => $this->site->id,
                        'batch_id' => $this->batchId,
                        'provider' => 'openai',
                        'prompt_template' => $this->prompt ?: 'Enhance and expand the following content',
                        'input_data' => [
                            'title' => $title,
                            'slug' => $slug,
                            'tone' => $this->tone,
                            'word_count' => $this->wordCount,
                            'generation_method' => $this->generationMethod,
                            'template_id' => $this->templateId,
                            'mapped_data' => $mappedData,
                        ],
                        'status' => 'pending',
                        'attempts' => 0,
                    ]);

                    try {
                        $aiService = app(AIServiceInterface::class);
                        $aiPrompt = $this->buildBulkPrompt($title, $content, $mappedData);
                        $response = $aiService->generate($aiPrompt, [
                            'max_tokens' => (int) ($this->wordCount * 1.5),
                        ]);

                        $content = $response->content;

                        $job->update([
                            'output_content' => $content,
                            'tokens_used' => $response->tokensUsed,
                            'cost_cents' => $response->estimatedCostCents,
                            'status' => 'completed',
                        ]);
                    } catch (\Exception $e) {
                        $job->update([
                            'status' => 'failed',
                            'error_message' => $e->getMessage(),
                            'attempts' => 1,
                        ]);
                        // Continue with template-only content if AI fails
                    }
                }

                $page = Page::create([
                    'tenant_id' => auth()->id(),
                    'site_id' => $this->site->id,
                    'title' => $title,
                    'slug' => $slug,
                    'content_html' => $content,
                    'meta_title' => Str::limit($title, 60),
                    'meta_description' => Str::limit(strip_tags($content), 155),
                    'generation_method' => GenerationMethod::from($this->generationMethod),
                    'status' => ContentStatus::Draft,
                    'template_id' => $this->templateId,
                    'variable_data' => $mappedData,
                ]);

                // Update the generation job with the page ID if it exists
                if (isset($job)) {
                    $job->update(['page_id' => $page->id]);
                }

                $this->successCount++;
            } catch (\Exception $e) {
                $this->failCount++;
                report($e);
            }
        }

        $this->generating = false;

        if ($this->failCount === 0) {
            session()->flash('success', "Successfully generated {$this->successCount} pages!");
        } else {
            session()->flash('warning', "Generated {$this->successCount} pages with {$this->failCount} failures.");
        }
    }

    /**
     * Build AI prompt for bulk generation.
     */
    protected function buildBulkPrompt(string $title, string $templateContent, array $data): string
    {
        $prompt = "Write a {$this->wordCount}-word article.\n";
        $prompt .= "Title: {$title}\n";
        $prompt .= "Tone: {$this->tone}\n";

        if ($this->prompt) {
            $prompt .= "Topic/Focus: {$this->prompt}\n";
        }

        if ($templateContent) {
            $prompt .= "\nBase template content to enhance and expand:\n{$templateContent}\n";
        }

        $dataContext = '';
        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                $dataContext .= "- {$key}: {$value}\n";
            }
        }
        if ($dataContext) {
            $prompt .= "\nAvailable data:\n{$dataContext}";
        }

        $prompt .= "\nReturn the content as clean, semantic HTML with proper headings (h2, h3), paragraphs, and lists.";
        $prompt .= "\nDo NOT include the title as an h1 tag or any <html>/<head>/<body> wrapper tags.";
        $prompt .= "\nMake the content unique, engaging, and SEO-optimised.";

        return $prompt;
    }

    public function render()
    {
        return view('livewire.app.content.bulk-page-generator', [
            'dataSources' => DataSource::where('site_id', $this->site->id)->get(),
            'templates' => PageTemplate::where(function ($q) {
                $q->where('is_system', true)->orWhere('tenant_id', auth()->id());
            })->get(),
        ]);
    }
}
