<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'price_monthly', 'price_yearly',
        'stripe_monthly_price_id', 'stripe_yearly_price_id', 'features',
        'max_weddings', 'max_guests', 'max_tables',
        'has_sms', 'has_email_invitations', 'has_collaboration',
        'has_advanced_table_plan', 'has_unlimited_checklist',
        'is_active', 'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'price_monthly' => 'decimal:2',
        'price_yearly' => 'decimal:2',
        'has_sms' => 'boolean',
        'has_email_invitations' => 'boolean',
        'has_collaboration' => 'boolean',
        'has_advanced_table_plan' => 'boolean',
        'has_unlimited_checklist' => 'boolean',
        'is_active' => 'boolean',
        'max_weddings' => 'integer',
        'max_guests' => 'integer',
        'max_tables' => 'integer',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function isFree(): bool
    {
        return $this->slug === 'free';
    }
}
