<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, Billable;

    protected $fillable = [
        'name', 'email', 'password',
        'is_admin', 'plan_id', 'plan_expires_at', 'ai_credits_used',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'plan_expires_at' => 'datetime',
        ];
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function sites(): HasMany
    {
        return $this->hasMany(Site::class, 'tenant_id');
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'tenant_id');
    }

    public function canCreateSite(): bool
    {
        $plan = $this->plan;
        if (!$plan) return false;
        if ($plan->isUnlimited('max_sites')) return true;
        return $this->sites()->count() < $plan->max_sites;
    }

    public function canCreatePage(Site $site): bool
    {
        $plan = $this->plan;
        if (!$plan) return false;
        if ($plan->isUnlimited('max_pages_per_site')) return true;
        return $site->pages()->count() < $plan->max_pages_per_site;
    }

    public function hasAiCredits(): bool
    {
        $plan = $this->plan;
        if (!$plan) return false;
        if ($plan->isUnlimited('max_ai_credits_monthly')) return true;
        return $this->ai_credits_used < $plan->max_ai_credits_monthly;
    }
}
