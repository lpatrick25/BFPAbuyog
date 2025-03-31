<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class ApplicationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;

    public function __construct($application)
    {
        $this->application = $application;
    }

    // Choose notification channels (Database & Broadcasting)
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    // Store in database
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'A new application has been submitted.',
            'application_id' => $this->application->id
        ];
    }

    // Send real-time notification via Pusher
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => 'A new application has been submitted.',
            'application_id' => $this->application->id
        ]);
    }
}
