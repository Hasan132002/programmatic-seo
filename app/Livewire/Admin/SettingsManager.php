<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Artisan;
use Livewire\Component;

class SettingsManager extends Component
{
    public string $platform_name = '';
    public string $platform_domain = '';
    public string $default_ai_provider = '';
    public string $default_ai_model = '';
    public string $openai_api_key = '';
    public string $maintenance_output = '';

    public function mount(): void
    {
        $this->platform_name = config('app.name', 'Programmatic SEO');
        $this->platform_domain = config('app.url', 'localhost');
        $this->default_ai_provider = config('ai.default_provider', 'openai');
        $this->default_ai_model = config('ai.providers.openai.model', 'gpt-4o-mini');
        $this->openai_api_key = $this->maskApiKey(config('ai.providers.openai.api_key', env('OPENAI_API_KEY', '')));
    }

    private function maskApiKey(?string $key): string
    {
        if (!$key || strlen($key) < 8) {
            return $key ? '****' : 'Not set';
        }

        return substr($key, 0, 4) . str_repeat('*', strlen($key) - 8) . substr($key, -4);
    }

    public function clearCache(): void
    {
        try {
            Artisan::call('cache:clear');
            session()->flash('success', 'Application cache has been cleared.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    public function clearViews(): void
    {
        try {
            Artisan::call('view:clear');
            session()->flash('success', 'Compiled views have been cleared.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Failed to clear views: ' . $e->getMessage());
        }
    }

    public function optimizeApp(): void
    {
        try {
            Artisan::call('optimize');
            $this->maintenance_output = trim(Artisan::output());
            session()->flash('success', 'Application optimized successfully.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Optimization failed: ' . $e->getMessage());
        }
    }

    public function restartQueue(): void
    {
        try {
            Artisan::call('queue:restart');
            session()->flash('success', 'Queue restart signal sent. Workers will restart after current job.');
        } catch (\Throwable $e) {
            session()->flash('error', 'Failed to restart queue: ' . $e->getMessage());
        }
    }

    public function getSystemInfo(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'environment' => app()->environment(),
            'debug_mode' => config('app.debug') ? 'Enabled' : 'Disabled',
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'queue_driver' => config('queue.default'),
            'mail_driver' => config('mail.default'),
            'database' => config('database.default'),
            'timezone' => config('app.timezone'),
        ];
    }

    public function getMailConfig(): array
    {
        return [
            'driver' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host', 'N/A'),
            'port' => config('mail.mailers.smtp.port', 'N/A'),
            'from_address' => config('mail.from.address', 'N/A'),
            'from_name' => config('mail.from.name', 'N/A'),
        ];
    }

    public function getStats(): array
    {
        return [
            'total_users' => \App\Models\User::count(),
            'total_sites' => \App\Models\Site::count(),
            'total_pages' => \App\Models\Page::count(),
            'total_views' => \App\Models\PageView::count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.settings-manager', [
            'systemInfo' => $this->getSystemInfo(),
            'mailConfig' => $this->getMailConfig(),
            'stats' => $this->getStats(),
        ]);
    }
}
