<?php

namespace App\Observers;

use App\Models\Schedule;
use App\Mail\ScheduleNotification;
use App\Models\Application;
use App\Models\ApplicationStatus;
use Illuminate\Support\Facades\Mail;

class ScheduleObserver
{
    /**
     * Handle the Schedule "created" event.
     */
    public function created(Schedule $schedule)
    {
        // Retrieve related models
        $application = $schedule->application;
        $establishment = $application->establishment ?? null;
        $client = $establishment->client ?? null;

        if ($client && $client->email) {
            Mail::to($client->email)->send(new ScheduleNotification($schedule, 'Inspection'));
        }

        $application = Application::findOrFail($schedule->application_id);

        // Create application status
        ApplicationStatus::create([
            'application_id' => $application->id,
            'status' => 'Scheduled for Inspection',
        ]);
    }

    /**
     * Handle the Schedule "updated" event.
     */
    public function updated(Schedule $schedule): void
    {
        // Retrieve related models
        $application = $schedule->application;
        $establishment = $application->establishment ?? null;
        $client = $establishment->client ?? null;

        if ($client && $client->email) {
            Mail::to($client->email)->send(new ScheduleNotification($schedule, 'Reschedule'));
        }
    }

    /**
     * Handle the Schedule "deleted" event.
     */
    public function deleted(Schedule $schedule): void
    {
        //
    }

    /**
     * Handle the Schedule "restored" event.
     */
    public function restored(Schedule $schedule): void
    {
        //
    }

    /**
     * Handle the Schedule "force deleted" event.
     */
    public function forceDeleted(Schedule $schedule): void
    {
        //
    }
}
