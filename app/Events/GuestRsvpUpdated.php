<?php

namespace App\Events;

use App\Models\Guest;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GuestRsvpUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Guest $guest,
        public readonly string $previousStatus
    ) {}
}
