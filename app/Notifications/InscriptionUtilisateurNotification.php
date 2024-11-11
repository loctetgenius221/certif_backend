<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InscriptionUtilisateurNotification extends Notification
{
    use Queueable;

    protected $email;
    protected $password;
    protected $role;

    /**
     * Create a new notification instance.
     */
    public function __construct($email, $password, $role)
    {
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
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
        $role = ucfirst($this->role);

        return (new MailMessage)
        ->subject('Bienvenue sur Fann Consult')
        ->greeting('Bonjour ' . $notifiable->nom . ' ' . $notifiable->prenom)
        ->line("Vous avez été ajouté en tant que {$this->role} dans la plateforme Fann Consult.")
        ->line("Voici vos identifiants :")
        ->line("Email : " . $this->email)
        ->line("Mot de passe : " . $this->password)
        ->action('Connexion', url('/login'))
        ->line("Merci de vous connecter et de changer votre mot de passe pour sécuriser votre compte.");

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
