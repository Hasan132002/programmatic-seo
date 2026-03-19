<?php

namespace App\Services\AI;

use InvalidArgumentException;

/**
 * Factory for creating AI service instances by provider name.
 *
 * Centralises the construction logic so the rest of the application
 * can request an AI service without knowing the concrete implementation.
 */
class AIServiceFactory
{
    /**
     * Create an AI service instance for the given provider.
     *
     * @param string|null $provider  The provider key (e.g. 'openai'). Defaults to config value.
     *
     * @return AIServiceInterface
     *
     * @throws InvalidArgumentException If the provider is not supported.
     */
    public static function make(?string $provider = null): AIServiceInterface
    {
        $provider = $provider ?? config('ai.default_provider', 'openai');

        return match ($provider) {
            'openai' => new OpenAIService(),
            default  => throw new InvalidArgumentException(
                "Unsupported AI provider: '{$provider}'. Supported providers: openai.",
            ),
        };
    }
}
