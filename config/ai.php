<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default AI Provider
    |--------------------------------------------------------------------------
    |
    | The default AI provider to use for content generation. Currently only
    | 'openai' is supported. Additional providers can be added by implementing
    | the AIServiceInterface and registering them in AIServiceFactory.
    |
    */

    'default_provider' => env('AI_PROVIDER', 'openai'),

    /*
    |--------------------------------------------------------------------------
    | AI Provider Configurations
    |--------------------------------------------------------------------------
    |
    | Configuration for each supported AI provider. Each provider entry
    | contains model selection, token limits, and pricing information.
    |
    */

    'providers' => [

        'openai' => [

            /*
            | The OpenAI model to use for chat completions.
            | Recommended: 'gpt-4o-mini' for cost-effective generation,
            |              'gpt-4o' for higher quality output.
            */
            'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),

            /*
            | Maximum number of tokens to generate in a single completion.
            | Higher values allow longer content but increase cost and latency.
            */
            'max_tokens' => (int) env('OPENAI_MAX_TOKENS', 4000),

            /*
            | Default temperature for generation (0.0 - 2.0).
            | Lower values produce more focused, deterministic output.
            | Higher values produce more creative, varied output.
            */
            'temperature' => (float) env('OPENAI_TEMPERATURE', 0.7),

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Content Generation Defaults
    |--------------------------------------------------------------------------
    */

    'content' => [
        'default_word_count' => 1000,
        'max_word_count' => 5000,
        'default_tone' => 'professional',
        'available_tones' => [
            'professional' => 'Professional',
            'casual' => 'Casual & Friendly',
            'academic' => 'Academic',
            'persuasive' => 'Persuasive',
            'informative' => 'Informative',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cost Tracking (per 1M tokens, in dollars)
    |--------------------------------------------------------------------------
    */

    'costs' => [
        'gpt-4o-mini' => ['input' => 0.15, 'output' => 0.60],
        'gpt-4o' => ['input' => 2.50, 'output' => 10.00],
        'gpt-3.5-turbo' => ['input' => 0.50, 'output' => 1.50],
    ],

];
