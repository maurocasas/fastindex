<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
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

    static function boot()
    {
        parent::boot();

        self::creating(function(Page $page) {
            $page->site_id = $page->sitemap->site_id;
        });
    }

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function sitemap(): BelongsTo
    {
        return $this->belongsTo(Sitemap::class);
    }

    public function getStatusAttribute(): string
    {
        return [
            'Submitted and indexed' => 'success',
            'Pending' => 'pending',
            'Discovered - currently not indexed' => 'pending',
            null => 'pending'
        ][$this->coverage_state] ?? 'error';
    }

    public function scopeScrapeable(Builder $builder): void
    {
        $builder->where(function ($query) {
            $query->whereNull('crawled_at')
                ->orWhere('crawled_at', '<=', now()->subDay());
        });
    }

    public function getInternalDescriptionAttribute()
    {
        return $this->path;
    }

    public function getInternalUrlAttribute(): string
    {
        return route('pages', $this->site);
    }
}
