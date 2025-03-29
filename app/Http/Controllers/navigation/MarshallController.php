<?php

namespace App\Http\Controllers\navigation;

use App\Http\Controllers\Controller;
use App\Mail\ScheduleNotification;
use App\Models\ApplicationStatus;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MarshallController extends Controller
{
    public function dashboards()
    {
        try {
            // $notifications = auth()->user()->unreadNotifications;

            return view('marshall.dashboard');
            // return view('marshall.dashboard', compact('notifications'));
        } catch (\Exception $e) {
            Log::error('Error retrieving Dashboard View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Dashboard View not found.'], 500);
        }
    }
    public function establishments()
    {
        try {
            return view('marshall.establishment');
        } catch (\Exception $e) {
            Log::error('Error retrieving Establishment View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Establishment View not found.'], 500);
        }
    }

    public function applicants()
    {
        try {
            // Retrieve all users with the role "Inspector" and eager-load the related Inspector model
            $inspectors = User::where('role', 'Inspector')->with('inspector')->get();

            // Pass the data to the view
            return view('marshall.application', compact('inspectors'));
        } catch (\Exception $e) {
            Log::error('Error retrieving Application View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Application View not found.'], 500);
        }
    }

    public function schedule()
    {
        try {
            // Retrieve all users with the role "Inspector" and eager-load the related Inspector model
            $inspectors = User::where('role', 'Inspector')->with('inspector')->get();

            // Pass the data to the view
            return view('marshall.schedule', compact('inspectors'));
        } catch (\Exception $e) {
            Log::error('Error retrieving Application View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Application View not found.'], 500);
        }
    }

    public function changeSchedule(Request $request, $application_id)
    {
        try {
            $request->validate([
                'reschedule_date' => 'nullable|date|after_or_equal:today',
            ]);

            $schedule = Schedule::where('application_id', $application_id)->first();
            $schedule->schedule_date = $request->reschedule_date;
            $schedule->save();

            // Retrieve related models
            $application = $schedule->application;
            $establishment = $application->establishment ?? null;
            $client = $establishment->client ?? null;

            if ($client && $client->email) {
                Mail::to($client->email)->send(new ScheduleNotification($schedule, 'Reschedule'));
            }

            Log::info('Inspection has been successfully rescheduled', ['schedule_id' => $schedule->id]);

            return response()->json(['message' => 'Inspection has been successfully rescheduled'], 201);
        } catch (\Exception $e) {
            Log::error('Error changing the inspection date', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error changing the inspection date'], 500);
        }
    }
}
