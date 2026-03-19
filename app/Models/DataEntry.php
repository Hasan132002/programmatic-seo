<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataEntry extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'data_source_id', 'data', 'checksum',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    public function dataSource(): BelongsTo
    {
        return $this->belongsTo(DataSource::class);
    }
}
