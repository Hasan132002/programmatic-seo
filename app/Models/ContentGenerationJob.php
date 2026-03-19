<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSite;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentGenerationJob extends Model
{
    use BelongsToTenant, BelongsToSite;

    protected $table = 'content_generation_jobs';

    protected $fillable = [
        'tenant_id', 'site_id', 'page_id', 'batch_id',
        'provider', 'prompt_template', 'input_data',
        'output_content', 'tokens_used', 'cost_cents',
        'status', 'error_message', 'attempts',
    ];

    protected function casts(): array
    {
        return [
            'input_data' => 'array',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
