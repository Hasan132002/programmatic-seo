<?php

namespace App\Models;

use App\Enums\DataSourceType;
use App\Models\Concerns\BelongsToSite;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataSource extends Model
{
    use BelongsToTenant, BelongsToSite;

    protected $fillable = [
        'tenant_id', 'site_id', 'name', 'type',
        'config', 'last_synced_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => DataSourceType::class,
            'config' => 'array',
            'last_synced_at' => 'datetime',
        ];
    }

    public function entries(): HasMany
    {
        return $this->hasMany(DataEntry::class);
    }
}
