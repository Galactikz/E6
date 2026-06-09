<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeddingCollaborator extends Model
{
    use HasFactory;

    protected $fillable = [
        'wedding_id', 'user_id', 'email', 'role', 'token', 'status', 'accepted_at',
    ];

    protected $casts = [
        'accepted_at' => 'datetime',
    ];

    public function wedding(): BelongsTo
    {
        return $this->belongsTo(Wedding::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }
}
