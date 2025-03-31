<?php

namespace App\Observers;

use App\Mail\ApplicationStatusUpdated;
use App\Models\ApplicationStatus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ApplicationStatusObserver
{
    /**
     * Handle the ApplicationStatus "updated" event.
     */
    public function updated(ApplicationStatus $applicationStatus)
    {
        // Retrieve related models
        $application = $applicationStatus->application;
        $establishment = $application->establishment ?? null;
        $client = $establishment->client ?? null;

        if ($client && $client->email) {
            // Send email to client
            Mail::to($client->email)->send(new ApplicationStatusUpdated($applicationStatus));
        } else {
            Log::error('Email not sent');
        }
    }
}
