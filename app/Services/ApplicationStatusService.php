<?php

namespace App\Services;

use App\Models\Application;
use App\Models\ApplicationStatus;
use App\Models\Schedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ApplicationStatusService
{
    public function updateApplicationStatus($request, $application_id)
    {
        $schedule = Schedule::where('application_id', $application_id)->first();

        $applicationStatus = ApplicationStatus::where('application_id', $application_id)
            ->where('status', $request->status === 'Certificate Approval Pending' ? 'Scheduled for Inspection' : $request->status)
            ->whereNull('remarks')
            ->firstOrFail();

        if ($request->status === 'Certificate Approval Pending') {
            $schedule->update(['status' => 'Completed']);
        }

        $applicationStatus->update(['remarks' => $request->remarks]);

        ApplicationStatus::create([
            'application_id' => $application_id,
            'status' => $request->status,
        ]);

        if ($request->hasFile('image_proof')) {
            Session::put('skip_schedule_notification_again', true);
            Application::findOrFail($application_id)
                ->addMedia($request->file('image_proof'))
                ->usingName('Proof of Investigation')
                ->toMediaCollection('fsic_requirements');
        }

        if ($request->filled('schedule_date')) {
            Session::put('skip_schedule_notification', true);
            $schedule->update(['schedule_date' => $request->schedule_date]);
        }

        return response()->json(['message' => 'Application status updated'], 201);
    }
}
