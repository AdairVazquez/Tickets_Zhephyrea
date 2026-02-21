<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MiResetPassword extends Notification
{
    use Queueable;

    // 1. Declaras la propiedad pública
    public $token;

    /**
     * 2. Recibes el token en el constructor
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Ahora $this->token ya tiene valor
        return (new MailMessage)
            ->subject('Recupera tu acceso a Zephyrea')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Vimos que solicitaste restablecer tu contraseña.')
            ->action('Resetear Contraseña', url(route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false)))
            ->line('Si no fuiste tú, ignora este mensaje.');
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
