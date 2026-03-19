<?php

namespace App\Models;

use App\Enums\NicheType;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PageTemplate extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'site_id', 'name', 'slug', 'niche_type',
        'layout_json', 'layout_html', 'layout_css',
        'variable_schema', 'is_system',
    ];

    protected function casts(): array
    {
        return [
            'niche_type' => NicheType::class,
            'variable_schema' => 'array',
            'is_system' => 'boolean',
        ];
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'template_id');
    }
}
