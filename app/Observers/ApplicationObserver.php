<?php

namespace App\Observers;

use App\Events\NewApplication;
use App\Models\Application;
use App\Models\ApplicationStatus;
use App\Models\User;
use App\Notifications\ApplicationNotification;
use Illuminate\Support\Facades\Notification;

class ApplicationObserver
{
    /**
     * Handle the Application "created" event.
     */
    public function created(Application $application)
    {
        // Create application status
        ApplicationStatus::create([
            'application_id' => $application->id,
            'application_number' => $application->application_number,
            'status' => 'Under Review',
        ]);

        // Broadcast New Application
        broadcast(new NewApplication($application))->toOthers();
    }

    /**
     * Handle the Application "updated" event.
     */
    public function updated(Application $application): void
    {
        //
    }

    /**
     * Handle the Application "deleted" event.
     */
    public function deleted(Application $application): void
    {
        //
    }

    /**
     * Handle the Application "restored" event.
     */
    public function restored(Application $application): void
    {
        //
    }

    /**
     * Handle the Application "force deleted" event.
     */
    public function forceDeleted(Application $application): void
    {
        //
    }
}
