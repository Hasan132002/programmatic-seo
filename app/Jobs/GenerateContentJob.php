<?php

namespace App\Jobs;

use App\Enums\ContentStatus;
use App\Models\Page;
use App\Services\Content\ContentPipeline;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Queued job for generating content for a single page.
 *
 * Dispatched by the bulk generation system or individual page editor.
 * Uses the ContentPipeline service to orchestrate template rendering
 * and/or AI content generation. Updates the ContentGenerationJob record
 * in the database to track status, output, and error information.
 */
class GenerateContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     */
    public int $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @param Page $page  The page to generate content for.
     */
    public function __construct(
        public Page $page,
    ) {}

    /**
     * Execute the job.
     *
     * Runs the ContentPipeline to generate content for the page.
     * On success, updates the associated ContentGenerationJob record
     * with the output content and completed status. On failure,
     * records the error message and marks the generation as failed.
     *
     * @param ContentPipeline $pipeline  Injected by the service container.
     */
    public function handle(ContentPipeline $pipeline): void
    {
        // Mark page as generating
        $this->page->update(['status' => ContentStatus::Generating->value]);

        // Find the associated content_generation_jobs record if one exists
        $generationRecord = DB::table('content_generation_jobs')
            ->where('page_id', $this->page->id)
            ->where('status', 'pending')
            ->orderByDesc('id')
            ->first();

        try {
            $pipeline->generate($this->page);

            // Update generation record on success
            if ($generationRecord) {
                DB::table('content_generation_jobs')
                    ->where('id', $generationRecord->id)
                    ->update([
                        'status'         => 'completed',
                        'output_content' => $this->page->content_html,
                        'attempts'       => ($generationRecord->attempts ?? 0) + 1,
                        'updated_at'     => now(),
                    ]);
            }

            Log::info("Content generated successfully for page [{$this->page->id}].");

        } catch (Throwable $e) {
            // Mark page as failed
            $this->page->update(['status' => ContentStatus::Failed->value]);

            // Update generation record on failure
            if ($generationRecord) {
                DB::table('content_generation_jobs')
                    ->where('id', $generationRecord->id)
                    ->update([
                        'status'        => 'failed',
                        'error_message' => $e->getMessage(),
                        'attempts'      => ($generationRecord->attempts ?? 0) + 1,
                        'updated_at'    => now(),
                    ]);
            }

            Log::error("Content generation failed for page [{$this->page->id}]: {$e->getMessage()}");

            // Re-throw to allow Laravel's retry mechanism to work
            throw $e;
        }
    }

    /**
     * Handle a job failure after all retries are exhausted.
     *
     * @param Throwable $exception  The exception that caused the final failure.
     */
    public function failed(Throwable $exception): void
    {
        // Ensure page is marked as failed
        $this->page->update(['status' => ContentStatus::Failed->value]);

        // Update generation record with final failure status
        DB::table('content_generation_jobs')
            ->where('page_id', $this->page->id)
            ->where('status', '!=', 'completed')
            ->orderByDesc('id')
            ->limit(1)
            ->update([
                'status'        => 'failed',
                'error_message' => 'All retries exhausted. Last error: ' . $exception->getMessage(),
                'updated_at'    => now(),
            ]);

        Log::error(
            "Content generation permanently failed for page [{$this->page->id}] after {$this->tries} attempts: {$exception->getMessage()}"
        );
    }
}
