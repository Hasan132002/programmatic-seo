<?php

namespace App\Livewire\App\Content;

use App\Enums\ContentStatus;
use App\Enums\GenerationMethod;
use App\Models\ContentGenerationJob;
use App\Models\Page;
use App\Models\Site;
use App\Services\AI\AIServiceInterface;
use Illuminate\Support\Str;
use Livewire\Component;

class KeywordPageGenerator extends Component
{
    public Site $site;

    // Input
    public string $keywordsInput = '';
    public string $tone = 'professional';
    public int $wordCount = 1000;
    public string $customInstructions = '';
    public string $slugPrefix = '';

    // Generation state
    public bool $generating = false;
    public int $progress = 0;
    public int $total = 0;
    public int $successCount = 0;
    public int $failCount = 0;
    public ?string $batchId = null;
    public array $results = [];
    public string $currentKeyword = '';

    protected function rules(): array
    {
        return [
            'keywordsInput' => 'required|string|min:2',
            'tone' => 'required|in:professional,casual,academic,friendly,persuasive',
            'wordCount' => 'required|integer|min:300|max:5000',
            'customInstructions' => 'nullable|string|max:2000',
            'slugPrefix' => 'nullable|string|max:100',
        ];
    }

    protected $messages = [
        'keywordsInput.required' => 'Please enter at least one keyword.',
        'keywordsInput.min' => 'Keywords must be at least 2 characters.',
    ];

    public function mount(Site $site): void
    {
        $this->site = $site;
    }

    /**
     * Parse keywords from the textarea input.
     */
    public function getKeywordsProperty(): array
    {
        $lines = preg_split('/[\r\n]+/', trim($this->keywordsInput));
        return array_values(array_filter(array_map('trim', $lines), fn ($k) => strlen($k) > 0));
    }

    /**
     * Generate pages for all entered keywords.
     */
    public function generateAll(): void
    {
        $this->validate();

        $keywords = $this->keywords;

        if (empty($keywords)) {
            $this->addError('keywordsInput', 'Please enter at least one keyword.');
            return;
        }

        if (count($keywords) > 500) {
            $this->addError('keywordsInput', 'Maximum 500 keywords allowed at once.');
            return;
        }

        $this->generating = true;
        $this->progress = 0;
        $this->total = count($keywords);
        $this->successCount = 0;
        $this->failCount = 0;
        $this->results = [];
        $this->batchId = Str::uuid()->toString();

        $aiService = app(AIServiceInterface::class);

        foreach ($keywords as $index => $keyword) {
            $this->progress = $index + 1;
            $this->currentKeyword = $keyword;

            try {
                $title = $this->buildTitle($keyword);
                $slug = $this->buildSlug($keyword);

                // Ensure unique slug
                $slugBase = $slug;
                $counter = 1;
                while (Page::withoutGlobalScopes()->where('site_id', $this->site->id)->where('slug', $slug)->exists()) {
                    $slug = $slugBase . '-' . $counter;
                    $counter++;
                }

                // Track the generation job
                $job = ContentGenerationJob::create([
                    'tenant_id' => auth()->id(),
                    'site_id' => $this->site->id,
                    'batch_id' => $this->batchId,
                    'provider' => 'openai',
                    'prompt_template' => 'keyword_page_generator',
                    'input_data' => [
                        'keyword' => $keyword,
                        'title' => $title,
                        'slug' => $slug,
                        'tone' => $this->tone,
                        'word_count' => $this->wordCount,
                        'custom_instructions' => $this->customInstructions,
                        'niche_type' => $this->site->niche_type?->value ?? 'custom',
                    ],
                    'status' => 'pending',
                    'attempts' => 0,
                ]);

                // Build AI prompt
                $prompt = $this->buildPrompt($keyword, $title);

                // Generate content
                $response = $aiService->generate($prompt, [
                    'max_tokens' => (int) ($this->wordCount * 1.5),
                ]);

                // Create the page
                $page = Page::create([
                    'tenant_id' => auth()->id(),
                    'site_id' => $this->site->id,
                    'title' => $title,
                    'slug' => $slug,
                    'content_html' => $response->content,
                    'meta_title' => Str::limit($title, 60),
                    'meta_description' => Str::limit(strip_tags($response->content), 155),
                    'generation_method' => GenerationMethod::AI,
                    'status' => ContentStatus::Draft,
                ]);

                // Update job as completed
                $job->update([
                    'page_id' => $page->id,
                    'output_content' => $response->content,
                    'tokens_used' => $response->tokensUsed,
                    'cost_cents' => $response->estimatedCostCents,
                    'status' => 'completed',
                ]);

                $this->results[] = [
                    'keyword' => $keyword,
                    'title' => $title,
                    'slug' => $slug,
                    'page_id' => $page->id,
                    'status' => 'success',
                    'tokens' => $response->tokensUsed,
                ];

                $this->successCount++;
            } catch (\Exception $e) {
                if (isset($job)) {
                    $job->update([
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                        'attempts' => 1,
                    ]);
                }

                $this->results[] = [
                    'keyword' => $keyword,
                    'title' => $title ?? $keyword,
                    'slug' => $slug ?? Str::slug($keyword),
                    'status' => 'failed',
                    'error' => Str::limit($e->getMessage(), 100),
                ];

                $this->failCount++;
                report($e);
            }
        }

        $this->generating = false;
        $this->currentKeyword = '';

        if ($this->failCount === 0) {
            session()->flash('success', "Successfully generated {$this->successCount} pages from keywords!");
        } else {
            session()->flash('error', "Generated {$this->successCount} pages, {$this->failCount} failed.");
        }
    }

    /**
     * Build a page title from a keyword.
     */
    protected function buildTitle(string $keyword): string
    {
        $nicheType = $this->site->niche_type?->value ?? 'custom';

        return match ($nicheType) {
            'city', 'location' => ucwords($keyword) . ' - Complete Local Guide',
            'comparison' => ucwords($keyword) . ' - Detailed Comparison & Review',
            'directory' => ucwords($keyword) . ' - Directory & Listings',
            default => ucwords($keyword),
        };
    }

    /**
     * Build a URL slug from a keyword.
     */
    protected function buildSlug(string $keyword): string
    {
        $slug = Str::slug($keyword);

        if ($this->slugPrefix) {
            $slug = Str::slug($this->slugPrefix) . '/' . $slug;
        }

        return $slug;
    }

    /**
     * Build the AI prompt for a given keyword.
     */
    protected function buildPrompt(string $keyword, string $title): string
    {
        $nicheType = $this->site->niche_type?->value ?? 'custom';

        $prompt = "Write a comprehensive, SEO-optimised {$this->wordCount}-word article.\n";
        $prompt .= "Target Keyword: {$keyword}\n";
        $prompt .= "Title: {$title}\n";
        $prompt .= "Tone: {$this->tone}\n";
        $prompt .= "Niche: {$nicheType}\n";

        // Niche-specific instructions
        $prompt .= match ($nicheType) {
            'city', 'location' => "\nFocus on local information, neighborhoods, attractions, cost of living, transportation, and insider tips for this location. Include practical details visitors and residents would find useful.\n",
            'comparison' => "\nProvide an objective comparison covering features, pricing, pros and cons, user reviews, and recommendations. Include a comparison summary at the end.\n",
            'directory' => "\nCreate a comprehensive directory-style listing with categories, descriptions, contact information placeholders, and ratings. Format as a useful reference guide.\n",
            default => "\nCreate informative, well-structured content that thoroughly covers the topic.\n",
        };

        if ($this->customInstructions) {
            $prompt .= "\nAdditional Instructions: {$this->customInstructions}\n";
        }

        $prompt .= "\nReturn the content as clean, semantic HTML with proper headings (h2, h3), paragraphs, lists, and tables where appropriate.";
        $prompt .= "\nDo NOT include the title as an h1 tag.";
        $prompt .= "\nDo NOT include <html>, <head>, <body>, or <doctype> tags.";
        $prompt .= "\nMake the content unique, engaging, and optimised for the target keyword.";
        $prompt .= "\nInclude a compelling introduction and a clear conclusion.";
        $prompt .= "\nNaturally incorporate the target keyword throughout the content (aim for 1-2% keyword density).";

        return $prompt;
    }

    /**
     * Reset the form to start fresh.
     */
    public function resetForm(): void
    {
        $this->reset([
            'keywordsInput', 'customInstructions', 'slugPrefix',
            'generating', 'progress', 'total', 'successCount', 'failCount',
            'results', 'batchId', 'currentKeyword',
        ]);
        $this->tone = 'professional';
        $this->wordCount = 1000;
    }

    public function render()
    {
        return view('livewire.app.content.keyword-page-generator', [
            'parsedKeywords' => $this->keywords,
            'recentJobs' => ContentGenerationJob::where('site_id', $this->site->id)
                ->where('prompt_template', 'keyword_page_generator')
                ->latest()
                ->take(10)
                ->get(),
        ]);
    }
}
