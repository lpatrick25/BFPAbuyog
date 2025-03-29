<?php

namespace App\Http\Controllers\navigation;

use App\Http\Controllers\Controller;
use App\Models\Establishment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    public function dashboards()
    {
        try {
            return view('client.dashboard');
        } catch (\Exception $e) {
            Log::error('Error retrieving Dashboard View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Dashboard View not found.'], 500);
        }
    }

    public function establishments()
    {
        try {
            return view('client.establishment');
        } catch (\Exception $e) {
            Log::error('Error retrieving Establishment View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Establishment View not found.'], 500);
        }
    }

    public function applications()
    {
        try {
            return view('client.application');
        } catch (\Exception $e) {
            Log::error('Error retrieving Application View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Application View not found.'], 500);
        }
    }

    public function schedules()
    {
        try {
            return view('client.schedule');
        } catch (\Exception $e) {
            Log::error('Error retrieving Schedule View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Schedule View not found.'], 500);
        }
    }

    public function addEstablishment()
    {
        try {
            return view('establishment.create');
        } catch (\Exception $e) {
            Log::error('Error retrieving Establishment View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Establishment View not found.'], 500);
        }
    }

    public function addApplication()
    {
        try {
            $client = optional(auth()->user())->client;
            $establishments = Establishment::where('client_id', $client->id)->get();
            return view('application.create', compact('establishments'));
        } catch (\Exception $e) {
            Log::error('Error retrieving Application View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Application View not found.'], 500);
        }
    }

    public function editEstablishment($sessionID)
    {
        try {
            // Check if session exists and is not expired
            if (!session()->has($sessionID) || now()->greaterThan(session()->get('session_expiry_' . $sessionID))) {
                return redirect()->route('establishment.list')->with('error', 'Session expired.');
            }

            // Retrieve the establishment ID from the session
            $userId = session()->get($sessionID);

            // Fetch the establishment data
            $establishment = Establishment::findOrFail($userId);

            return view('establishment.update', compact('establishment'));
        } catch (\Exception $e) {
            Log::error('Error retrieving Establishment View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Establishment View not found.'], 500);
        }
    }

    public function generateSessionToken($userID)
    {
        try {
            // Generate a unique session ID and cast it to a string
            $sessionID = (string) Str::uuid();

            // Store the session ID with expiration (1 hour)
            session([$sessionID => $userID]);
            session()->put('session_expiry_' . $sessionID, now()->addHour());

            return response()->json(['sessionID' => $sessionID]);
        } catch (\Exception $e) {
            Log::error('Error generating session token', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to generate session token.'], 500);
        }
    }

    public function getEstablishmentApplication($establishmentId)
    {
        try {
            // Fetch establishment with applications and FSICs
            $establishment = Establishment::with(['applications.fsics'])->find($establishmentId);

            $haveFSICApplication = false;
            $haveFSICExpired = false;

            if ($establishment) {
                // Check if the establishment has applications
                if ($establishment->applications->isNotEmpty()) {
                    foreach ($establishment->applications as $application) {
                        // Check if the application has no FSIC (pending FSIC application)
                        if ($application->fsics->isEmpty()) {
                            $haveFSICApplication = true;
                        }

                        // Check if any FSIC is expired
                        foreach ($application->fsics as $fsic) {
                            if (now()->gt($fsic->expiration_date)) { // Check if expired
                                $haveFSICExpired = true;
                            }
                        }
                    }
                }
            }

            return response()->json([
                'haveFSICApplication' => $haveFSICApplication,
                'haveFSICExpired' => $haveFSICExpired,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error retrieving Establishment Application', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
}
