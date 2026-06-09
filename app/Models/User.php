<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_USER = 'user';
    const ROLE_PREMIUM = 'premium';
    const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'name', 'email', 'password',
        'google_id', 'apple_id', 'avatar', 'phone',
        'role', 'locale', 'marketing_consent',
        'last_login_at', 'stripe_customer_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
        'google_id', 'apple_id', 'stripe_customer_id',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'marketing_consent' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function weddings(): HasMany
    {
        return $this->hasMany(Wedding::class)->orderBy('created_at', 'desc');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(UserSubscription::class)
            ->where('status', 'active')
            ->latest();
    }

    public function isPremium(): bool
    {
        return $this->activeSubscription()->exists()
            && !$this->activeSubscription->plan->isFree();
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
    }

    public function currentPlan(): ?SubscriptionPlan
    {
        return $this->activeSubscription?->plan;
    }
}
