<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewUserNotification extends Notification
{
    use Queueable, SerializesModels;

    protected $newUser;
    protected $password;

    /**
     * Create a new notification instance.
     */
    public function __construct($user, $password = null)
    {
        $this->newUser = $user;
        $this->password = $password;
    }

    public function build()
    {
        $url = url('/login');

        return $this->subject('Bienvenue sur notre plateforme Fann Consult')
            ->view('emails.new-user')
            ->with([
                'userName' => $this->user->prenom . ' ' . $this->user->nom,
                'email' => $this->user->email,
                'password' => $this->password,
                'loginUrl' => $url
            ]);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    // public function via(object $notifiable): array
    // {
    //     return ['mail', 'database'];
    // }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     $url = url('/login');

    //     return (new MailMessage)
    //         ->subject('Bienvenue sur notre plateforme Fann Consult')
    //         ->greeting('Bonjour ' . $this->newUser->prenom . ' ' . $this->newUser->nom)
    //         ->line('Un compte a été créé pour vous sur notre plateforme.')
    //         ->line('Vos identifiants de connexion :')
    //         ->line('Email : ' . $this->newUser->email)
    //         ->line('Mot de passe : ' . $this->password)
    //         ->action('Se connecter', $url)
    //         ->line('Merci de changer votre mot de passe lors de votre première connexion.');
    // }

    /**
     * Get the database representation of the notification.
     */
    // public function toDatabase($notifiable): array
    // {
    //     // Récupérer le rôle de l'utilisateur via Spatie
    //     $role = $this->newUser->getRoleNames()->first(); // Utilise `first()` si un seul rôle est attribué

    //     return [
    //         'user_id' => $this->newUser->id,
    //         'role' => $role,
    //         'name' => $this->newUser->prenom . ' ' . $this->newUser->nom,
    //         'message' => "Nouveau " . $role . " inscrit : " . $this->newUser->prenom . ' ' . $this->newUser->nom,
    //         'created_at' => now()
    //     ];
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    // public function toArray(object $notifiable): array
    // {
    //     return [
    //         'user_id' => $this->newUser->id,
    //         'role' => $this->newUser->getRoleNames()->first(),
    //         'name' => $this->newUser->prenom . ' ' . $this->newUser->nom,
    //     ];
    // }
}
