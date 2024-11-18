<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ConfirmationRendezVousNotification extends Notification
{
    use Queueable;

    protected $rendezVous;

    /**
     * Create a new notification instance.
     */
    public function __construct($rendezVous)
    {
        $this->rendezVous = $rendezVous;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirmation de votre rendez-vous')
            ->greeting('Bonjour ' . $notifiable->nom . ' ' . $notifiable->prenom)
            ->line('Votre rendez-vous a bien été pris en compte.')
            ->line('Date : ' . \Carbon\Carbon::parse($this->rendezVous->date)->format('d/m/Y'))
            ->line('Heure : ' . $this->rendezVous->heure_debut . ' - ' . $this->rendezVous->heure_fin)
            ->line('Vous recevrez des rappels pour ce rendez-vous.')
            ->action('Voir mes rendez-vous', url('/mes-rendez-vous'))
            ->line('Merci d\'utiliser notre plateforme.');

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
