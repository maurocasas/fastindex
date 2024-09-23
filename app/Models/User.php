<?php

namespace App\Models;

use App\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property mixed $password
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property UserRole $role
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property-read string $initials
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Site> $sites
 * @property-read int|null $sites_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'role', 'name', 'email', 'password',
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function (User $user) {
            $user->role = UserRole::MEMBER;
        });
    }

    public function getInitialsAttribute(): string
    {
        return implode('', array_map(fn ($item) => substr($item, 0, 1), explode(' ', strtoupper($this->name))));
    }

    public function sites(): HasMany
    {
        return $this->hasMany(Site::class);
    }

    protected function casts(): array
    {
        return [
            'role' => UserRole::class,
            'last_login_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'refresh_token' => 'encrypted',
            'polling_gsc' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function checkPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }
}
