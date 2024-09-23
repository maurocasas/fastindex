<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\ServiceAccounts\Log;

class ServiceAccount extends Model
{
    use HasFactory;

    protected $fillable = ['credentials'];

    protected $casts = [
        'credentials' => 'json',
        'validated_at' => 'datetime',
    ];

    public function sites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class, 'service_account_sites');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(Log::class);
    }

    public function scopeAvailable(Builder $builder): void
    {
        $builder
            ->withCount(['logs' => function (Builder $logs) {
                $logs->where('created_at', '>=', now()->subHours(24));
            }])
            ->where('logs_count', '<=', file_get_contents(config_path('daily_quota')));
    }

}
