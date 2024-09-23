<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    use HasFactory;

    protected $fillable = ['hostname', 'gsc_name', 'favicon', 'auto_index'];

    protected $casts = [
        'refreshing_sitemaps' => 'boolean',
        'auto_index' => 'boolean',
        'resume_at' => 'datetime'
    ];

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function sitemaps(): HasMany
    {
        return $this->hasMany(Sitemap::class);
    }

    public function service_accounts(): BelongsToMany
    {
        return $this->belongsToMany(ServiceAccount::class, 'service_account_sites');
    }

    public function getConsoleAlertAttribute(): bool
    {
        return $this->service_accounts()->count() > 1;
    }

    public function getInternalDescriptionAttribute()
    {
        return $this->hostname;
    }

    public function getInternalUrlAttribute(): string
    {
        return route('sites.show', $this);
    }

}
