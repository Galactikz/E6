<?php

namespace App\Events;

use App\Models\Wedding;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WeddingCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public readonly Wedding $wedding) {}
}
