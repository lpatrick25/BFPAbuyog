<?php

namespace App\Observers;

use App\Models\ApplicationStatus;
use App\Notifications\ApplicationStatusNotification;
use Illuminate\Support\Facades\Log;

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
    public function updated(ApplicationStatus $applicationStatus): void
    {
        try {
            // Send Notification to the user
            $applicationStatus->notify(new ApplicationStatusNotification($applicationStatus));

            // Log success
            Log::info("Application status notification sent successfully", ['application_id' => $applicationStatus->application_id]);
        } catch (\Exception $e) {
            Log::error("Failed to send application status notification", [
                'application_id' => $applicationStatus->application_id,
                'error' => $e->getMessage()
            ]);
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
