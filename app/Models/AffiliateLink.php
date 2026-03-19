<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSite;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class AffiliateLink extends Model
{
    use BelongsToTenant, BelongsToSite;

    protected $fillable = [
        'tenant_id', 'site_id', 'original_url',
        'affiliate_url', 'keyword', 'clicks',
    ];
}
