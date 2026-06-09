<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TableSeat extends Model
{
    use HasFactory;

    protected $fillable = ['reception_table_id', 'guest_id', 'seat_number'];

    protected $casts = [
        'seat_number' => 'integer',
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(ReceptionTable::class, 'reception_table_id');
    }

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function isOccupied(): bool
    {
        return $this->guest_id !== null;
    }
}
