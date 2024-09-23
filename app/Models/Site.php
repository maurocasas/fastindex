<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property int $id
 * @property string $gsc_name
 * @property string $hostname
 * @property string|null $favicon
 * @property bool $auto_index
 * @property bool $refreshing_sitemaps
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read bool $console_alert
 * @property-read mixed $internal_description
 * @property-read string $internal_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Page> $pages
 * @property-read int|null $pages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ServiceAccount> $service_accounts
 * @property-read int|null $service_accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sitemap> $sitemaps
 * @property-read int|null $sitemaps_count
 * @method static \Illuminate\Database\Eloquent\Builder|Site newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Site newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Site query()
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereAutoIndex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereFavicon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereGscName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereHostname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereRefreshingSitemaps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Site whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Site extends Model
{
    use HasFactory;

    protected $fillable = ['hostname', 'gsc_name', 'favicon', 'auto_index'];

    protected $casts = [
        'refreshing_sitemaps' => 'boolean',
        'auto_index' => 'boolean',
        'resume_at' => 'datetime',
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
