<?php

namespace App\Models;

use App\Enums\NicheType;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Site extends Model
{
    use BelongsToTenant, SoftDeletes;

    protected $fillable = [
        'tenant_id', 'name', 'slug', 'domain', 'subdomain',
        'niche_type', 'settings', 'seo_defaults',
        'adsense_publisher_id', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'niche_type' => NicheType::class,
            'settings' => 'array',
            'seo_defaults' => 'array',
            'is_published' => 'boolean',
        ];
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function publishedPages(): HasMany
    {
        return $this->pages()->where('status', 'published');
    }

    public function getUrlAttribute(): string
    {
        if ($this->domain) {
            return 'https://' . $this->domain;
        }

        $appUrl = config('app.url');
        $parsed = parse_url($appUrl);
        $scheme = $parsed['scheme'] ?? 'http';
        $host = $parsed['host'] ?? 'localhost';
        $port = isset($parsed['port']) ? ':' . $parsed['port'] : '';

        return $scheme . '://' . $this->subdomain . '.' . $host . $port;
    }
}
