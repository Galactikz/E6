<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReceptionTable extends Model
{
    use HasFactory;

    const SHAPE_ROUND = 'round';
    const SHAPE_RECTANGULAR = 'rectangular';

    protected $fillable = [
        'table_plan_id', 'name', 'shape', 'capacity',
        'position', 'color', 'sort_order',
    ];

    protected $casts = [
        'position' => 'array',
        'capacity' => 'integer',
        'sort_order' => 'integer',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(TablePlan::class, 'table_plan_id');
    }

    public function seats(): HasMany
    {
        return $this->hasMany(TableSeat::class)->orderBy('seat_number');
    }

    public function getAvailableSeatsAttribute(): int
    {
        return $this->capacity - $this->seats->whereNotNull('guest_id')->count();
    }

    public function scopeRound($query)
    {
        return $query->where('shape', self::SHAPE_ROUND);
    }

    public function scopeRectangular($query)
    {
        return $query->where('shape', self::SHAPE_RECTANGULAR);
    }
}
