<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class ImageGenerationService
{
    /**
     * Generate an image from a text prompt using the specified provider.
     *
     * @param string $prompt The text description of the image to generate.
     * @param array $options Provider-specific options:
     *   - 'provider': 'pollinations' (free) | 'openai' (paid, requires API key)
     *   - 'api_key': Required for paid providers
     *   - 'size': Image dimensions (default '1024x1024')
     *   - 'model': For OpenAI, 'dall-e-3' or 'dall-e-2'
     *   - 'style': For DALL-E 3, 'vivid' or 'natural'
     *
     * @return array{url: string, local_path: string|null, provider: string}
     */
    public function generate(string $prompt, array $options = []): array
    {
        $provider = $options['provider'] ?? 'pollinations';

        return match ($provider) {
            'openai', 'dall-e' => $this->generateWithOpenAI($prompt, $options),
            'pollinations' => $this->generateWithPollinations($prompt, $options),
            default => throw new RuntimeException("Unsupported image provider: {$provider}"),
        };
    }

    /**
     * Generate using Pollinations.ai (FREE, no API key needed).
     * Returns a URL that generates the image on-the-fly.
     */
    protected function generateWithPollinations(string $prompt, array $options = []): array
    {
        $width = $options['width'] ?? 1024;
        $height = $options['height'] ?? 680;
        $seed = $options['seed'] ?? random_int(1, 999999);

        // Pollinations generates images via URL - no API call needed
        $encodedPrompt = urlencode($prompt);
        $url = "https://image.pollinations.ai/prompt/{$encodedPrompt}?width={$width}&height={$height}&seed={$seed}&nologo=true";

        // Optionally download and save locally
        $localPath = null;
        if ($options['save_locally'] ?? false) {
            $localPath = $this->downloadAndSave($url, $options['site_id'] ?? 0);
        }

        return [
            'url' => $url,
            'local_path' => $localPath,
            'provider' => 'pollinations',
        ];
    }

    /**
     * Generate using OpenAI DALL-E API (paid, requires API key).
     */
    protected function generateWithOpenAI(string $prompt, array $options = []): array
    {
        $apiKey = $options['api_key'] ?? config('services.openai.api_key') ?? env('OPENAI_API_KEY');

        if (!$apiKey) {
            throw new RuntimeException('OpenAI API key is required for DALL-E image generation.');
        }

        $model = $options['model'] ?? 'dall-e-3';
        $size = $options['size'] ?? '1024x1024';
        $style = $options['style'] ?? 'natural';
        $quality = $options['quality'] ?? 'standard';

        $response = Http::withHeaders([
            'Authorization' => "Bearer {$apiKey}",
            'Content-Type' => 'application/json',
        ])->timeout(60)->post('https://api.openai.com/v1/images/generations', [
            'model' => $model,
            'prompt' => $prompt,
            'n' => 1,
            'size' => $size,
            'style' => $style,
            'quality' => $quality,
        ]);

        if ($response->failed()) {
            $error = $response->json('error.message', 'Unknown error');
            throw new RuntimeException("DALL-E API error: {$error}");
        }

        $imageUrl = $response->json('data.0.url');

        if (!$imageUrl) {
            throw new RuntimeException('DALL-E returned no image URL.');
        }

        $localPath = null;
        if ($options['save_locally'] ?? false) {
            $localPath = $this->downloadAndSave($imageUrl, $options['site_id'] ?? 0);
        }

        return [
            'url' => $imageUrl,
            'local_path' => $localPath,
            'provider' => 'openai',
        ];
    }

    /**
     * Build a prompt optimized for image generation from page context.
     */
    public function buildPromptFromContext(string $title, string $nicheType, array $variables = []): string
    {
        $base = "Professional, high-quality editorial image for a web article";

        return match ($nicheType) {
            'city' => "{$base} about {$variables['service_name'] ?? 'services'} in {$variables['city_name'] ?? 'a city'}, {$variables['state'] ?? ''}. Modern cityscape, professional service imagery, clean and inviting. No text overlays.",
            'comparison' => "{$base} comparing {$variables['product_a'] ?? 'Product A'} vs {$variables['product_b'] ?? 'Product B'} in {$variables['category'] ?? 'technology'}. Side-by-side product comparison concept, clean white background, professional product photography style. No text overlays.",
            'directory' => "{$base} for {$variables['business_name'] ?? 'a business'} ({$variables['category'] ?? 'services'}) in {$variables['city'] ?? 'a city'}. Professional storefront or office, welcoming and trustworthy. No text overlays.",
            default => "{$base} titled \"{$title}\". Professional, relevant visual, clean design, editorial quality. No text overlays.",
        };
    }

    /**
     * Download an image from URL and save to local storage.
     */
    protected function downloadAndSave(string $url, int $siteId): ?string
    {
        try {
            $response = Http::timeout(30)->get($url);

            if ($response->failed()) {
                return null;
            }

            $filename = "sites/{$siteId}/images/" . md5($url . time()) . '.jpg';
            Storage::disk('public')->put($filename, $response->body());

            return $filename;
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Get available providers with their configuration.
     */
    public static function availableProviders(): array
    {
        return [
            'pollinations' => [
                'name' => 'Pollinations AI',
                'type' => 'free',
                'description' => 'Free AI image generation, no API key needed',
                'requires_key' => false,
            ],
            'openai' => [
                'name' => 'DALL-E (OpenAI)',
                'type' => 'paid',
                'description' => 'High-quality images via DALL-E 3, requires OpenAI API key',
                'requires_key' => true,
                'models' => ['dall-e-3', 'dall-e-2'],
            ],
        ];
    }
}
