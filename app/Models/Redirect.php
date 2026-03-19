<?php

namespace App\Models;

use App\Models\Concerns\BelongsToSite;
use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    use BelongsToSite;

    protected $fillable = [
        'site_id', 'from_path', 'to_path', 'status_code',
    ];
}
