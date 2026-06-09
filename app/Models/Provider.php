<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provider extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'provider_category_id', 'name', 'slug', 'description',
        'phone', 'email', 'website', 'city', 'department', 'region', 'zip_code',
        'latitude', 'longitude', 'price_from', 'price_to',
        'rating', 'review_count', 'is_premium', 'is_verified', 'is_active',
        'gallery', 'meta',
    ];

    protected $casts = [
        'gallery' => 'array',
        'meta' => 'array',
        'is_premium' => 'boolean',
        'is_verified' => 'boolean',
        'is_active' => 'boolean',
        'price_from' => 'decimal:2',
        'price_to' => 'decimal:2',
        'latitude' => 'float',
        'longitude' => 'float',
        'rating' => 'integer',
        'review_count' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProviderCategory::class, 'provider_category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInCity($query, string $city)
    {
        return $query->where('city', 'ILIKE', "%{$city}%");
    }

    public function scopeInRegion($query, string $region)
    {
        return $query->where('region', 'ILIKE', "%{$region}%");
    }

    public function scopeInDepartment($query, string $department)
    {
        return $query->where('department', 'ILIKE', "%{$department}%");
    }

    public function scopeInBudget($query, ?float $min, ?float $max)
    {
        if ($min !== null) $query->where('price_from', '>=', $min);
        if ($max !== null) $query->where('price_to', '<=', $max);
        return $query;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
