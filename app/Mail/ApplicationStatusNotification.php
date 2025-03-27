<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $clientName;
    public $establishmentName;
    public $establishmentLocation;
    public $applicationStatus;

    /**
     * Create a new message instance.
     */
    public function __construct($clientName, $establishmentName, $establishmentLocation, $applicationStatus)
    {
        $this->clientName = $clientName;
        $this->establishmentName = $establishmentName;
        $this->establishmentLocation = $establishmentLocation;
        $this->applicationStatus = $applicationStatus;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Application Status Update')
            ->view('emails.application_status_notification')
            ->with([
                'clientName' => $this->clientName,
                'establishmentName' => $this->establishmentName,
                'establishmentLocation' => $this->establishmentLocation,
                'applicationStatus' => $this->applicationStatus,
            ]);
    }
}
