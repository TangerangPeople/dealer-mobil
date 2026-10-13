<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';

    public const ROLE_OWNER = 'owner';

    public const ROLE_SALES = 'sales';

    public const ROLE_MECHANIC = 'mechanic';

    public const ROLE_USER = 'user';

    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_OWNER,
        self::ROLE_SALES,
        self::ROLE_MECHANIC,
        self::ROLE_USER,
    ];

    public const STAFF_ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_OWNER,
        self::ROLE_SALES,
        self::ROLE_MECHANIC,
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'whatsapp',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isOwner(): bool
    {
        return $this->role === self::ROLE_OWNER;
    }

    public function isSales(): bool
    {
        return $this->role === self::ROLE_SALES;
    }

    public function isMechanic(): bool
    {
        return $this->role === self::ROLE_MECHANIC;
    }

    public function isStaff(): bool
    {
        return in_array($this->role, self::STAFF_ROLES, true);
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    /** @return HasMany<PurchaseApplication, $this> */
    public function purchaseApplications(): HasMany
    {
        return $this->hasMany(PurchaseApplication::class);
    }
}
