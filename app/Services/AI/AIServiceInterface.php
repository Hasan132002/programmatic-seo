<?php

namespace App\Services\AI;

/**
 * Contract for AI content generation services.
 *
 * All AI providers (OpenAI, Anthropic, etc.) must implement this interface
 * to ensure consistent behavior across the content generation pipeline.
 */
interface AIServiceInterface
{
    /**
     * Generate content from a prompt using the AI provider.
     *
     * @param string $prompt   The input prompt for content generation.
     * @param array  $options  Optional provider-specific parameters (max_tokens, temperature, etc.).
     *
     * @return AIResponse  Value object containing the generated content and usage metadata.
     *
     * @throws \RuntimeException If the AI provider request fails.
     */
    public function generate(string $prompt, array $options = []): AIResponse;

    /**
     * Estimate the cost in cents for generating content from the given prompt.
     *
     * This is a rough estimate based on prompt token count and the provider's
     * pricing model. It does not account for output tokens.
     *
     * @param string $prompt  The input prompt to estimate cost for.
     *
     * @return int  Estimated cost in cents.
     */
    public function estimateCost(string $prompt): int;
}
