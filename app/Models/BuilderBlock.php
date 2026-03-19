<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class BuilderBlock extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'name', 'category',
        'preview_image', 'component_json',
        'default_data', 'is_system',
    ];

    protected function casts(): array
    {
        return [
            'component_json' => 'array',
            'default_data' => 'array',
            'is_system' => 'boolean',
        ];
    }
}
