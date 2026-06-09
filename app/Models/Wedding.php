<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Wedding extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'wedding_date', 'city',
        'venue_name', 'guest_count', 'total_budget', 'slug', 'is_active',
    ];

    protected $casts = [
        'wedding_date' => 'date',
        'total_budget' => 'decimal:2',
        'is_active' => 'boolean',
        'guest_count' => 'integer',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $wedding) {
            if (empty($wedding->slug)) {
                $wedding->slug = Str::slug($wedding->name . '-' . now()->year);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function budgetCategories(): HasMany
    {
        return $this->hasMany(BudgetCategory::class)->orderBy('sort_order');
    }

    public function budgetItems(): HasMany
    {
        return $this->hasMany(BudgetItem::class);
    }

    public function checklistTasks(): HasMany
    {
        return $this->hasMany(ChecklistTask::class)->orderBy('sort_order');
    }

    public function guestGroups(): HasMany
    {
        return $this->hasMany(GuestGroup::class)->orderBy('sort_order');
    }

    public function guests(): HasMany
    {
        return $this->hasMany(Guest::class);
    }

    public function tablePlans(): HasMany
    {
        return $this->hasMany(TablePlan::class);
    }

    public function activePlan(): HasOne
    {
        return $this->hasOne(TablePlan::class)->where('is_active', true);
    }

    public function collaborators(): HasMany
    {
        return $this->hasMany(WeddingCollaborator::class);
    }

    public function shareLinks(): HasMany
    {
        return $this->hasMany(ShareLink::class);
    }

    public function getTotalPlannedBudgetAttribute(): float
    {
        return $this->budgetCategories->sum('planned_amount');
    }

    public function getTotalActualBudgetAttribute(): float
    {
        return $this->budgetCategories->sum('actual_amount');
    }

    public function getConfirmedGuestsCountAttribute(): int
    {
        return $this->guests()->where('rsvp_status', 'confirmed')->count();
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
