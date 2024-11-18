<?php

namespace App\Notifications;

use App\Models\RendezVous;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\DB;

class NewAppointmentNotification extends Notification
{
    use Queueable;

    protected $rendezVous;

    public function __construct(RendezVous $rendezVous)
    {
        $this->rendezVous = $rendezVous;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $patient = $this->rendezVous->patient->user;
        return (new MailMessage)
            ->subject('Nouveau rendez-vous programmé')
            ->greeting('Bonjour Dr. ' . $notifiable->prenom . ' ' . $notifiable->nom)
            ->line('Un nouveau rendez-vous a été programmé avec ' . $patient->nom . ' ' . $patient->prenom)
            ->line('Date: ' . \Carbon\Carbon::parse($this->rendezVous->date)->format('d/m/Y'))
            ->line('Heure: ' . $this->rendezVous->heure_debut . ' - ' . $this->rendezVous->heure_fin)
            ->line('Type: ' . $this->rendezVous->type_rendez_vous)
            ->line('Motif: ' . $this->rendezVous->motif)
            ->action('Voir le rendez-vous', url('/rendez-vous/' . $this->rendezVous->id))
            ->line('Merci d\'utiliser notre application!');
    }

    // public function toDatabase(object $notifiable): array
    // {
    //     $patient = $this->rendezVous->patient;
    //     return [
    //         'id' => null, // La base de données générera automatiquement l'ID
    //         'contenu' => sprintf(
    //             'Nouveau rendez-vous avec %s %s le %s à %s',
    //             $patient->nom,
    //             $patient->prenom,
    //             \Carbon\Carbon::parse($this->rendezVous->date)->format('d/m/Y'),
    //             $this->rendezVous->heure_debut
    //         ),
    //         'date_envoi' => now(),
    //         'destinataire_id' => $notifiable->id,
    //         'rendez_vous_id' => $this->rendezVous->id,
    //         'lu' => false
    //     ];
    // }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
