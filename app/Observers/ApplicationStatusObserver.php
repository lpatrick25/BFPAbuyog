<?php

namespace App\Observers;

use App\Mail\ApplicationStatusUpdated;
use App\Models\ApplicationStatus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ApplicationStatusObserver
{
    /**
     * Handle the ApplicationStatus "created" event.
     */
    public function created(ApplicationStatus $applicationStatus): void
    {
        //
    }

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

    /**
     * Handle the ApplicationStatus "deleted" event.
     */
    public function deleted(ApplicationStatus $applicationStatus): void
    {
        //
    }

    /**
     * Handle the ApplicationStatus "restored" event.
     */
    public function restored(ApplicationStatus $applicationStatus): void
    {
        //
    }

    /**
     * Handle the ApplicationStatus "force deleted" event.
     */
    public function forceDeleted(ApplicationStatus $applicationStatus): void
    {
        //
    }
}
