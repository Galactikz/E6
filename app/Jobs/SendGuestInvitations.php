<?php

namespace App\Jobs;

use App\Models\Guest;
use App\Notifications\GuestInvitationNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendGuestInvitations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public readonly array $guestIds) {}

    public function handle(): void
    {
        $guests = Guest::whereIn('id', $this->guestIds)
            ->whereNotNull('email')
            ->with('wedding')
            ->get();

        foreach ($guests as $guest) {
            try {
                $guest->notify(new GuestInvitationNotification($guest));
                $guest->update([
                    'invitation_sent' => true,
                    'invitation_sent_at' => now(),
                ]);
            } catch (\Throwable $e) {
                Log::error("Failed to send invitation to guest {$guest->id}: {$e->getMessage()}");
            }
        }
    }
}
