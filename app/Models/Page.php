<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 *
 *
 * @property int $id
 * @property int $site_id
 * @property int $sitemap_id
 * @property string $path
 * @property string|null $coverage_state
 * @property string|null $indexing_state
 * @property \Illuminate\Support\Carbon|null $crawled_at
 * @property bool $busy
 * @property bool $not_found
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $queried_at
 * @property \Illuminate\Support\Carbon|null $indexed_at
 * @property-read mixed $internal_description
 * @property-read string $internal_url
 * @property-read string $status
 * @property-read \App\Models\Site|null $site
 * @property-read \App\Models\Sitemap|null $sitemap
 * @method static Builder|Page newModelQuery()
 * @method static Builder|Page newQuery()
 * @method static Builder|Page query()
 * @method static Builder|Page scrapeable()
 * @method static Builder|Page whereBusy($value)
 * @method static Builder|Page whereCoverageState($value)
 * @method static Builder|Page whereCrawledAt($value)
 * @method static Builder|Page whereCreatedAt($value)
 * @method static Builder|Page whereId($value)
 * @method static Builder|Page whereIndexedAt($value)
 * @method static Builder|Page whereIndexingState($value)
 * @method static Builder|Page whereNotFound($value)
 * @method static Builder|Page wherePath($value)
 * @method static Builder|Page whereQueriedAt($value)
 * @method static Builder|Page whereSiteId($value)
 * @method static Builder|Page whereSitemapId($value)
 * @method static Builder|Page whereUpdatedAt($value)
 * @method static Builder|Page whereUrl($value)
 * @mixin \Eloquent
 */
class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id',
        'url',
        'path',
        'coverage_state',
        'indexing_state',
        'crawled_at',
        'busy',
        'not_found',
    ];

    protected $casts = [
        'crawled_at' => 'datetime',
        'queried_at' => 'datetime',
        'indexed_at' => 'datetime',
        'busy' => 'boolean',
        'not_found' => 'boolean',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function getStatusAttribute(): string
    {
        return [
            'Submitted and indexed' => 'success',
            'Pending' => 'pending',
            'Discovered - currently not indexed' => 'pending',
            null => 'pending',
        ][$this->coverage_state] ?? 'error';
    }

    public function scopeScrapeable(Builder $builder): void
    {
        $builder->where(function ($query) {
            $query->whereNull('crawled_at')
                ->orWhere('crawled_at', '<=', now()->subDay());
        });
    }

    public function getInternalUrlAttribute(): string
    {
        return route('pages', $this->site);
    }
}
