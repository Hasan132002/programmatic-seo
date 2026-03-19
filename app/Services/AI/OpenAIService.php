<?php

namespace App\Services\AI;

use OpenAI\Laravel\Facades\OpenAI;
use RuntimeException;
use Throwable;

/**
 * OpenAI implementation of the AI service interface.
 *
 * Uses the openai-php/laravel package to communicate with the OpenAI API.
 * Supports GPT-4o-mini and other chat completion models.
 */
class OpenAIService implements AIServiceInterface
{
    /**
     * The model identifier to use for chat completions.
     */
    protected string $model;

    /**
     * Default maximum tokens for completion responses.
     */
    protected int $maxTokens;

    public function __construct()
    {
        $this->model = config('ai.providers.openai.model', env('OPENAI_MODEL', 'gpt-4o-mini'));
        $this->maxTokens = (int) config('ai.providers.openai.max_tokens', 4000);
    }

    /**
     * Generate content using OpenAI's chat completion API.
     *
     * @param string $prompt   The user prompt for content generation.
     * @param array  $options  Optional overrides: 'model', 'max_tokens', 'temperature', 'system_message'.
     *
     * @return AIResponse  The generated content with usage metadata.
     *
     * @throws RuntimeException If the OpenAI API call fails.
     */
    public function generate(string $prompt, array $options = []): AIResponse
    {
        $model = $options['model'] ?? $this->model;
        $maxTokens = $options['max_tokens'] ?? $this->maxTokens;
        $temperature = $options['temperature'] ?? 0.7;

        $messages = [];

        // Add system message if provided
        if (isset($options['system_message'])) {
            $messages[] = [
                'role' => 'system',
                'content' => $options['system_message'],
            ];
        } else {
            $messages[] = [
                'role' => 'system',
                'content' => 'You are an expert SEO content writer. Generate high-quality, unique, well-structured HTML content optimized for search engines. Use semantic HTML tags. Do not include <html>, <head>, or <body> tags — only the inner content.',
            ];
        }

        $messages[] = [
            'role' => 'user',
            'content' => $prompt,
        ];

        try {
            $response = OpenAI::chat()->create([
                'model' => $model,
                'messages' => $messages,
                'max_tokens' => $maxTokens,
                'temperature' => $temperature,
            ]);

            $content = $response->choices[0]->message->content ?? '';
            $totalTokens = $response->usage->totalTokens ?? 0;

            // Cost estimate: ~$0.15 per 1M input tokens + ~$0.60 per 1M output tokens for gpt-4o-mini
            $costCents = (int) ceil($totalTokens * 0.15 / 1_000_000 * 100);

            return new AIResponse(
                content: $content,
                tokensUsed: $totalTokens,
                estimatedCostCents: $costCents,
                provider: 'openai',
            );
        } catch (Throwable $e) {
            throw new RuntimeException(
                "OpenAI API request failed: {$e->getMessage()}",
                (int) $e->getCode(),
                $e,
            );
        }
    }

    /**
     * Estimate the cost of processing the given prompt.
     *
     * Uses a rough heuristic: tokens ~= strlen / 4.
     * Cost based on gpt-4o-mini pricing: ~$0.15 per 1M tokens.
     *
     * @param string $prompt  The prompt to estimate cost for.
     *
     * @return int  Estimated cost in cents.
     */
    public function estimateCost(string $prompt): int
    {
        $estimatedTokens = (int) ceil(strlen($prompt) / 4);

        // $0.15 per 1M tokens -> convert to cents
        $costCents = (int) ceil($estimatedTokens * 0.15 / 1_000_000 * 100);

        return max($costCents, 1);
    }
}
