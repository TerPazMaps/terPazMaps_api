<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    private $token;
    public function __construct($token)
    {
        $this->token;
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
        $url = 'http://127.0.0.1:8000/resetpassword?token='. $this->token;
        $minutos = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');
        return (new MailMessage)
            ->subject('Atualização de senha')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('clique aqui para modificar a senha', $url)
            ->line('O link de recuperação expira em ' . $minutos . ' minuteo.')
            ->line('Se você não requistou recuperação de senha, por favor desconsidere esse email.');
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
