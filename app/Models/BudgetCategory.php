<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BudgetCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'wedding_id', 'name', 'slug', 'icon', 'color',
        'planned_amount', 'actual_amount', 'sort_order', 'is_custom',
    ];

    protected $casts = [
        'planned_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'is_custom' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BudgetItem::class);
    }

    public function getRemainingBudgetAttribute(): float
    {
        return (float) $this->planned_amount - (float) $this->actual_amount;
    }

    public function getUsagePercentageAttribute(): float
    {
        if ($this->planned_amount == 0) return 0;
        return round(($this->actual_amount / $this->planned_amount) * 100, 1);
    }

    public static function defaults(): array
    {
        return [
            ['name' => 'Salle', 'slug' => 'salle', 'icon' => 'building', 'color' => '#8B5CF6', 'sort_order' => 1],
            ['name' => 'Traiteur', 'slug' => 'traiteur', 'icon' => 'utensils', 'color' => '#EF4444', 'sort_order' => 2],
            ['name' => 'Photographe', 'slug' => 'photographe', 'icon' => 'camera', 'color' => '#3B82F6', 'sort_order' => 3],
            ['name' => 'Vidéaste', 'slug' => 'vidéaste', 'icon' => 'video', 'color' => '#10B981', 'sort_order' => 4],
            ['name' => 'DJ', 'slug' => 'dj', 'icon' => 'music', 'color' => '#F59E0B', 'sort_order' => 5],
            ['name' => 'Robe', 'slug' => 'robe', 'icon' => 'dress', 'color' => '#EC4899', 'sort_order' => 6],
            ['name' => 'Costume', 'slug' => 'costume', 'icon' => 'suit', 'color' => '#6366F1', 'sort_order' => 7],
            ['name' => 'Alliances', 'slug' => 'alliances', 'icon' => 'ring', 'color' => '#D97706', 'sort_order' => 8],
            ['name' => 'Décoration', 'slug' => 'decoration', 'icon' => 'sparkles', 'color' => '#14B8A6', 'sort_order' => 9],
            ['name' => 'Transport', 'slug' => 'transport', 'icon' => 'car', 'color' => '#64748B', 'sort_order' => 10],
            ['name' => 'Hébergement', 'slug' => 'hebergement', 'icon' => 'home', 'color' => '#7C3AED', 'sort_order' => 11],
            ['name' => 'Divers', 'slug' => 'divers', 'icon' => 'ellipsis', 'color' => '#9CA3AF', 'sort_order' => 12],
        ];
    }
}
