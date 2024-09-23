<?php

namespace App\Models;

use App\Models\ServiceAccounts\Log;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property int $id
 * @property array $credentials
 * @property \Illuminate\Support\Carbon|null $validated_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Log> $logs
 * @property-read int|null $logs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Site> $sites
 * @property-read int|null $sites_count
 * @method static Builder|ServiceAccount available()
 * @method static Builder|ServiceAccount newModelQuery()
 * @method static Builder|ServiceAccount newQuery()
 * @method static Builder|ServiceAccount query()
 * @method static Builder|ServiceAccount whereCreatedAt($value)
 * @method static Builder|ServiceAccount whereCredentials($value)
 * @method static Builder|ServiceAccount whereId($value)
 * @method static Builder|ServiceAccount whereUpdatedAt($value)
 * @method static Builder|ServiceAccount whereValidatedAt($value)
 * @mixin \Eloquent
 */
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
