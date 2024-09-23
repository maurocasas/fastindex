<?php

namespace App\Models;

use App\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'role', 'name', 'email', 'password'
    ];

    static function boot()
    {
        parent::boot();

        self::creating(function(User $user) {
            $user->role = UserRole::MEMBER;
        });
    }

    public function getInitialsAttribute(): string
    {
        return implode('', array_map(fn($item) => substr($item, 0, 1), explode(' ', strtoupper($this->name))));
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
            'password' => 'hashed'
        ];
    }

    public function checkPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }
}
