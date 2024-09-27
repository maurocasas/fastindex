<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 *
 * @property int $id
 * @property int $site_id
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $downloaded_at
 * @property \Illuminate\Support\Carbon|null $submitted_at
 * @property bool $pending
 * @property int $submitted
 * @property int $indexed
 * @property int $warnings
 * @property int $errors
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Page> $pages
 * @property-read int|null $pages_count
 * @property-read \App\Models\Site|null $site
 * @method static \Illuminate\Database\Eloquent\Builder|Sitemap newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sitemap newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sitemap query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sitemap whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sitemap whereErrors($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sitemap whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sitemap whereIndexed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sitemap whereLastDownloadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sitemap wherePending($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sitemap whereSiteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sitemap whereSubmitted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sitemap whereSubmittedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sitemap whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sitemap whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sitemap whereWarnings($value)
 * @mixin \Eloquent
 */
class Sitemap extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'downloaded_at',
        'submitted_at',
        'submitted',
        'indexed',
        'pending',
        'warnings',
        'errors',
        'busy'
    ];

    protected $casts = [
        'downloaded_at' => 'datetime',
        'submitted_at' => 'datetime',
        'pending' => 'boolean',
        'busy' => 'boolean',
        'content' => 'json',
        'is_index' => 'boolean'
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }

    public function toggleBusy(bool $status = true): void
    {
        $this->update([
            'busy' => $status
        ]);
    }
}
