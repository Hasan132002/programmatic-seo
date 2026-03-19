<?php

namespace App\Services\AI;

/**
 * Immutable value object representing a response from an AI provider.
 *
 * Encapsulates the generated content along with usage and cost metadata
 * returned by the AI service after a generation request.
 */
final readonly class AIResponse
{
    /**
     * @param string $content            The generated text content.
     * @param int    $tokensUsed         Total tokens consumed (prompt + completion).
     * @param int    $estimatedCostCents Estimated cost in cents for this generation.
     * @param string $provider           The AI provider identifier (e.g. 'openai').
     */
    public function __construct(
        public string $content,
        public int    $tokensUsed,
        public int    $estimatedCostCents,
        public string $provider,
    ) {}

    /**
     * Create an AIResponse from an OpenAI chat completion result.
     *
     * @param array  $result  The raw OpenAI API response array.
     * @param string $provider  The provider identifier.
     *
     * @return static
     */
    public static function fromOpenAI(array $result, string $provider = 'openai'): static
    {
        $content = $result['choices'][0]['message']['content'] ?? '';
        $totalTokens = $result['usage']['total_tokens'] ?? 0;

        // Rough cost estimate: ~$0.15 per 1M tokens for gpt-4o-mini input
        $costCents = (int) ceil($totalTokens * 0.15 / 1_000_000 * 100);

        return new static(
            content: $content,
            tokensUsed: $totalTokens,
            estimatedCostCents: $costCents,
            provider: $provider,
        );
    }
}
