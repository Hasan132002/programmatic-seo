<?php

namespace App\Providers;

use App\Services\AI\AIServiceFactory;
use App\Services\AI\AIServiceInterface;
use Illuminate\Support\ServiceProvider;

class PseoServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind AI Service Interface to factory-resolved instance
        $this->app->bind(AIServiceInterface::class, function ($app) {
            return AIServiceFactory::make(config('ai.default_provider', 'openai'));
        });
    }

    public function boot(): void
    {
        //
    }
}
