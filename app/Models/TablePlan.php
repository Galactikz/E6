<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TablePlan extends Model
{
    use HasFactory;

    protected $fillable = ['wedding_id', 'name', 'room_dimensions', 'is_active'];

    protected $casts = [
        'room_dimensions' => 'array',
        'is_active' => 'boolean',
    ];

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function tables(): HasMany
    {
        return $this->hasMany(ReceptionTable::class)->orderBy('sort_order');
    }

    public function getSeatedGuestsCountAttribute(): int
    {
        return $this->tables->sum(fn($table) => $table->seats->whereNotNull('guest_id')->count());
    }

    public function getTotalCapacityAttribute(): int
    {
        return $this->tables->sum('capacity');
    }
}
