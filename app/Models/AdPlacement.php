<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSite;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class AdPlacement extends Model
{
    use BelongsToTenant, BelongsToSite;

    protected $fillable = [
        'tenant_id', 'site_id', 'name', 'type',
        'code', 'position', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
