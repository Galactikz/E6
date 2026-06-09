<?php

namespace App\Services;

use App\Contracts\NotificationProviderInterface;
use App\Models\Guest;
use App\Models\GuestGroup;
use App\Models\Wedding;
use Illuminate\Support\Collection;

class GuestService
{
    public function __construct(
        private readonly ?NotificationProviderInterface $notificationProvider = null
    ) {}

    public function createGuest(Wedding $wedding, array $data): Guest
    {
        return Guest::create(array_merge($data, [
            'wedding_id' => $wedding->id,
        ]));
    }

    public function updateGuest(Guest $guest, array $data): Guest
    {
        $guest->update($data);
        return $guest->fresh(['group']);
    }

    public function updateRsvp(Guest $guest, string $status): Guest
    {
        $guest->update([
            'rsvp_status' => $status,
            'rsvp_responded_at' => now(),
        ]);
        return $guest->fresh();
    }

    public function sendInvitations(Wedding $wedding, array $guestIds): array
    {
        $sent = [];
        $failed = [];

        $guests = $wedding->guests()
            ->whereIn('id', $guestIds)
            ->whereNotNull('email')
            ->get();

        foreach ($guests as $guest) {
            try {
                $this->sendInvitationEmail($guest);
                $guest->update([
                    'invitation_sent' => true,
                    'invitation_sent_at' => now(),
                ]);
                $sent[] = $guest->id;
            } catch (\Throwable $e) {
                $failed[] = ['id' => $guest->id, 'error' => $e->getMessage()];
            }
        }

        return ['sent' => $sent, 'failed' => $failed];
    }

    private function sendInvitationEmail(Guest $guest): void
    {
        // Dispatched via Laravel Notifications
        $guest->notify(new \App\Notifications\GuestInvitationNotification($guest));
    }

    public function getStats(Wedding $wedding): array
    {
        $guests = $wedding->guests;

        return [
            'total' => $guests->count(),
            'confirmed' => $guests->where('rsvp_status', Guest::RSVP_CONFIRMED)->count(),
            'declined' => $guests->where('rsvp_status', Guest::RSVP_DECLINED)->count(),
            'pending' => $guests->where('rsvp_status', Guest::RSVP_PENDING)->count(),
            'adults' => $guests->where('meal_type', Guest::MEAL_ADULT)->count(),
            'children' => $guests->where('meal_type', Guest::MEAL_CHILD)->count(),
            'babies' => $guests->where('meal_type', Guest::MEAL_BABY)->count(),
            'with_dietary' => $guests->whereNotNull('dietary_requirements')->count(),
            'invitations_sent' => $guests->where('invitation_sent', true)->count(),
        ];
    }

    public function createGroup(Wedding $wedding, array $data): GuestGroup
    {
        return GuestGroup::create(array_merge($data, [
            'wedding_id' => $wedding->id,
        ]));
    }

    public function importFromCsv(Wedding $wedding, string $csvContent): array
    {
        $lines = str_getcsv($csvContent, "\n");
        $imported = 0;
        $errors = [];

        foreach ($lines as $index => $line) {
            if ($index === 0) continue;

            $columns = str_getcsv($line);

            if (count($columns) < 2) {
                $errors[] = "Ligne {$index}: données insuffisantes";
                continue;
            }

            try {
                $this->createGuest($wedding, [
                    'first_name' => trim($columns[0] ?? ''),
                    'last_name' => trim($columns[1] ?? ''),
                    'email' => trim($columns[2] ?? ''),
                    'phone' => trim($columns[3] ?? ''),
                    'rsvp_status' => Guest::RSVP_PENDING,
                    'meal_type' => Guest::MEAL_ADULT,
                ]);
                $imported++;
            } catch (\Throwable $e) {
                $errors[] = "Ligne {$index}: {$e->getMessage()}";
            }
        }

        return ['imported' => $imported, 'errors' => $errors];
    }
}
