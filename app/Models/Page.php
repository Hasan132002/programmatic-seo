<?php

namespace App\Models;

use App\Enums\ContentStatus;
use App\Enums\GenerationMethod;
use App\Models\Concerns\BelongsToSite;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use BelongsToTenant, BelongsToSite, SoftDeletes;

    protected $fillable = [
        'tenant_id', 'site_id', 'template_id', 'title', 'slug',
        'status', 'content_html', 'content_json', 'variable_data',
        'meta_title', 'meta_description', 'canonical_url',
        'schema_markup', 'og_image', 'priority',
        'generation_method', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => ContentStatus::class,
            'generation_method' => GenerationMethod::class,
            'content_json' => 'array',
            'variable_data' => 'array',
            'schema_markup' => 'array',
            'priority' => 'decimal:1',
            'published_at' => 'datetime',
        ];
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(PageTemplate::class, 'template_id');
    }

    public function isPublished(): bool
    {
        return $this->status === ContentStatus::Published;
    }

    public function getFullUrlAttribute(): string
    {
        return $this->site->url . '/' . $this->slug;
    }
}
