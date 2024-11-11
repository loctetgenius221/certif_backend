<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewPatientRegisteredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $patient;

    /**
     * Create a new notification instance.
     */
    public function __construct($patient)
    {
        $this->patient = $patient;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nouvelle inscription de patient')
            ->greeting('Bonjour Admin,')
            ->line("Un nouveau patient s'est inscrit sur la plateforme Fann Consult.")
            ->line("Nom : " . $this->patient->nom . ' ' . $this->patient->prenom)
            ->line("Email : " . $this->patient->email)
            ->line("Merci de vérifier et de valider cette inscription si nécessaire.");
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'contenu' => "Un nouveau patient s'est inscrit : {$this->patient->nom} {$this->patient->prenom} ({$this->patient->email}).",
            'date_envoi' => Carbon::now(),
            'destinataire_id' => $notifiable->id,  // ID de l'admin
            'rendez_vous_id' => null,  // Pas de rendez-vous pour cette notification
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [];
    }
}
