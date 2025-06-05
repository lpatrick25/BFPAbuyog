<?php

namespace App\Http\Controllers\navigation;

use App\Http\Controllers\Controller;
use App\Models\Establishment;
use App\Models\Fsic;
use App\Models\Schedule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InspectorController extends Controller
{
    public function dashboards()
    {
        try {
            $user = auth()->user();
            $inspector = $user->inspector;

            if (!$inspector) {
                throw new \Exception('Inspector profile not found.');
            }

            $metrics = [
                'issued_fsics' => Fsic::where('inspector_id', $inspector->id)->count(),
                'pending_schedules' => Schedule::where('inspector_id', $inspector->id)
                    ->where('status', 'Ongoing')
                    ->count(),
                'completed_schedules' => Schedule::where('inspector_id', $inspector->id)
                    ->where('status', 'Completed')
                    ->count(),
                'total_applications' => \App\Models\Application::whereHas('fsics', function ($query) use ($inspector) {
                    $query->where('inspector_id', $inspector->id);
                })->count(),
            ];

            return view('inspector.dashboard', compact('metrics', 'inspector'));
        } catch (\Exception $e) {
            Log::error('Error retrieving Inspector Dashboard View', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to load dashboard: ' . $e->getMessage());
        }
    }

    public function mapping()
    {
        try {
            $role = session('role');
            $response = [];
            $summary = [
                'inspected' => 0,
                'not_inspected' => 0,
                'not_applied' => 0,
            ];

            // Query establishments based on role
            $establishmentsQuery = Establishment::query()->with(['applications.fsics']);

            if ($role === 'client') {
                $client = auth()->user()->client;
                $establishmentsQuery->where('client_id', $client->id);
            }

            // Fetch establishments only once
            $establishments = $establishmentsQuery->get();

            if ($role === 'client' && $establishments->isEmpty()) {
                throw new \Exception('Establishment not found.');
            }

            foreach ($establishments as $establishment) {
                $application = $establishment->applications->first();
                $inspected = $application && $application->fsics->isNotEmpty();

                if (!$application) {
                    if (!in_array($role, ['marshall', 'inspector'])) {
                        $summary['not_applied']++;
                        $response[] = [
                            floatval($establishment->location_latitude),
                            floatval($establishment->location_longitude),
                            $establishment->id,
                            false,
                            'Not Applied'
                        ];
                    }
                } else {
                    $inspected ? $summary['inspected']++ : $summary['not_inspected']++;
                    $response[] = [
                        floatval($establishment->location_latitude),
                        floatval($establishment->location_longitude),
                        $establishment->id,
                        $inspected,
                        $inspected ? 'Inspected' : 'Not Inspected'
                    ];
                }
            }

            return view('inspector.mapping', compact('response', 'summary'));
        } catch (\Exception $e) {
            Log::error('Error retrieving Mapping View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Mapping View not found.'], 500);
        }
    }

    public function showEstablishment($sessionID)
    {
        try {
            // Check if session exists and is not expired
            if (!session()->has($sessionID) || now()->greaterThan(session()->get('session_expiry_' . $sessionID))) {
                return redirect()->route('inspector.mapping')->with('error', 'Session expired.');
            }

            // Retrieve the establishment ID from the session
            $userId = session()->get($sessionID);

            // Fetch the establishment data
            $establishment = Establishment::findOrFail($userId);

            return view('establishment.show', compact('establishment'));
        } catch (\Exception $e) {
            Log::error('Error retrieving Establishment View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Establishment View not found.'], 500);
        }
    }

    public function schedule()
    {
        try {
            return view('inspector.schedule');
        } catch (\Exception $e) {
            Log::error('Error retrieving Schedule View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Schedule View not found.'], 500);
        }
    }

    public function generateSessionToken($sessionId)
    {
        try {
            // Generate a unique session ID and cast it to a string
            $sessionID = (string) Str::uuid();

            // Store the session ID with expiration (1 hour)
            session([$sessionID => $sessionId]);
            session()->put('session_expiry_' . $sessionID, now()->addHour());

            return response()->json(['sessionID' => $sessionID]);
        } catch (\Exception $e) {
            Log::error('Error generating session token', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to generate session token.'], 500);
        }
    }
}
