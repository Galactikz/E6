<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Guest extends Model
{
    use HasFactory;

    const RSVP_PENDING = 'pending';
    const RSVP_CONFIRMED = 'confirmed';
    const RSVP_DECLINED = 'declined';

    const MEAL_ADULT = 'adult';
    const MEAL_CHILD = 'child';
    const MEAL_BABY = 'baby';

    protected $fillable = [
        'wedding_id', 'guest_group_id', 'first_name', 'last_name',
        'email', 'phone', 'rsvp_status', 'meal_type',
        'dietary_requirements', 'notes', 'invitation_sent',
        'invitation_sent_at', 'rsvp_token', 'rsvp_responded_at', 'plus_one',
    ];

    protected $casts = [
        'invitation_sent' => 'boolean',
        'invitation_sent_at' => 'datetime',
        'rsvp_responded_at' => 'datetime',
        'plus_one' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (self $guest) {
            if (empty($guest->rsvp_token)) {
                $guest->rsvp_token = Str::random(64);
            }
        });
    }

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(GuestGroup::class, 'guest_group_id');
    }

    public function tableSeats(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\TableSeat::class, 'guest_id');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function scopeConfirmed($query)
    {
        return $query->where('rsvp_status', self::RSVP_CONFIRMED);
    }

    public function scopeDeclined($query)
    {
        return $query->where('rsvp_status', self::RSVP_DECLINED);
    }

    public function scopePending($query)
    {
        return $query->where('rsvp_status', self::RSVP_PENDING);
    }
}
