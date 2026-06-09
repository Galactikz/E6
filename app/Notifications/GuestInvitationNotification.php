<?php

namespace App\Notifications;

use App\Models\Guest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GuestInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Guest $guest) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $wedding = $this->guest->wedding;
        $rsvpUrl = route('rsvp.show', $this->guest->rsvp_token);

        return (new MailMessage)
            ->subject("Vous êtes invité(e) au mariage de {$wedding->name}")
            ->greeting("Bonjour {$this->guest->first_name},")
            ->line("Vous êtes cordialement invité(e) à célébrer le mariage de **{$wedding->name}**.")
            ->when($wedding->wedding_date, fn($mail) =>
                $mail->line("📅 Date : {$wedding->wedding_date->format('d/m/Y')}")
            )
            ->when($wedding->city, fn($mail) =>
                $mail->line("📍 Lieu : {$wedding->city}")
            )
            ->action('Répondre à l\'invitation', $rsvpUrl)
            ->line('Nous espérons vous voir ce jour si spécial !')
            ->salutation('Avec nos sincères souhaits,');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'guest_id' => $this->guest->id,
            'wedding_id' => $this->guest->wedding_id,
            'type' => 'invitation',
        ];
    }
}
