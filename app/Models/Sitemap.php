<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sitemap extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'last_download_at',
        'submitted_at',
        'submitted',
        'indexed',
        'pending',
        'warnings',
        'errors',
    ];

    protected $casts = [
        'last_download_at' => 'datetime',
        'submitted_at' => 'datetime',
        'pending' => 'boolean',
        'content' => 'json'
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(Page::class);
    }
}
