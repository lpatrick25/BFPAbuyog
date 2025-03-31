<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification’s delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail']; // Send via email
    }

    /**
     * Get the email representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('User Account Creation')
            ->greeting("Hello, {$this->user->email}!")
            ->line('Your account has been successfully created.')
            ->action('Login Now', url('/login'))
            ->line('Thank you for joining us!');
    }
}
