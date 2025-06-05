<?php

namespace App\Http\Controllers\navigation;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Client;
use App\Models\Establishment;
use App\Models\Fsic;
use App\Models\Inspector;
use App\Models\Marshall;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function dashboards()
    {
        try {
            $metrics = [
                'pending_schedules' => Schedule::where('status', 'Ongoing')
                    ->count(),
                'completed_schedules' => Schedule::where('status', 'Completed')
                    ->count(),
                'total_establishments' => Establishment::count(),
                'total_applications' => Application::count(),
                'pending_applications' => Application::whereHas('applicationStatuses', function ($query) {
                    $query->where('status', 'Pending');
                })->count(),
                'completed_inspections' => Schedule::where('status', 'Completed')->count(),
                'issued_fsics' => Fsic::count(),
                'active_users' => User::where('is_active', true)->count(),
            ];

            return view('admin.dashboard', compact('metrics'));
        } catch (\Exception $e) {
            Log::error('Error retrieving Admin Dashboard View', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to load dashboard: ' . $e->getMessage());
        }
    }

    public function mapping()
    {
        try {
            // Ensure user is authenticated
            if (!auth()->check()) {
                throw new \Exception('User not authenticated.');
            }

            $user = auth()->user();
            $role = $user->role;
            $response = [];
            $summary = [
                'inspected' => 0,
                'not_inspected' => 0,
                'not_applied' => 0,
            ];

            // Query establishments with specific columns and relationships
            $establishmentsQuery = Establishment::query()
                ->select('id', 'location_latitude', 'location_longitude', 'client_id')
                ->with(['applications.fsics']);

            if ($role === 'client') {
                if (!$user->client) {
                    throw new \Exception('Client not found for user.');
                }
                $establishmentsQuery->where('client_id', $user->client->id);
            }

            $establishments = $establishmentsQuery->get();

            if ($role === 'client' && $establishments->isEmpty()) {
                throw new \Exception('No establishments found for this client.');
            }

            foreach ($establishments as $establishment) {
                // Validate coordinates
                $latitude = floatval($establishment->location_latitude);
                $longitude = floatval($establishment->location_longitude);
                if (!is_numeric($latitude) || !is_numeric($longitude)) {
                    Log::warning('Invalid coordinates for establishment', ['id' => $establishment->id]);
                    continue;
                }

                $application = $establishment->applications->first();
                $inspected = $application && $application->fsics->isNotEmpty();

                if (!$application && $role !== ' Marshall' && $role !== 'inspector') {
                    $summary['not_applied']++;
                    $response[] = [
                        $latitude,
                        $longitude,
                        $establishment->id,
                        false,
                        'Not Applied'
                    ];
                } else {
                    $inspected ? $summary['inspected']++ : $summary['not_inspected']++;
                    $response[] = [
                        $latitude,
                        $longitude,
                        $establishment->id,
                        $inspected,
                        $inspected ? 'Inspected' : 'Not Inspected'
                    ];
                }
            }

            return view('admin.mapping', compact('response', 'summary'));
        } catch (\Exception $e) {
            Log::error('Error retrieving Mapping View', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to load mapping view: ' . $e->getMessage());
        }
    }

    public function clients()
    {
        try {
            return view('admin.client');
        } catch (\Exception $e) {
            Log::error('Error retrieving Client View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Client View not found.'], 500);
        }
    }

    public function marshalls()
    {
        try {
            return view('admin.marshall');
        } catch (\Exception $e) {
            Log::error('Error retrieving Marshall View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Marshall View not found.'], 500);
        }
    }

    public function inspectors()
    {
        try {
            return view('admin.inspector');
        } catch (\Exception $e) {
            Log::error('Error retrieving Inspector View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Inspector View not found.'], 500);
        }
    }

    public function users()
    {
        try {
            return view('admin.users');
        } catch (\Exception $e) {
            Log::error('Error retrieving User View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'User View not found.'], 500);
        }
    }

    public function addClient()
    {
        try {
            return view('users.add_client');
        } catch (\Exception $e) {
            Log::error('Error retrieving Client View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Client View not found.'], 500);
        }
    }

    public function addMarshall()
    {
        try {
            return view('users.add_marshall');
        } catch (\Exception $e) {
            Log::error('Error retrieving Marshall View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Marshall View not found.'], 500);
        }
    }

    public function addInspector()
    {
        try {
            return view('users.add_inspector');
        } catch (\Exception $e) {
            Log::error('Error retrieving Inspector View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Inspector View not found.'], 500);
        }
    }

    public function editClient($sessionID)
    {
        try {
            // Check if session exists and is not expired
            if (!session()->has($sessionID) || now()->greaterThan(session()->get('session_expiry_' . $sessionID))) {
                return redirect()->route('client.list')->with('error', 'Session expired.');
            }

            // Retrieve the client ID from the session
            $userId = session()->get($sessionID);

            // Fetch the client data
            $client = Client::findOrFail($userId);

            return view('users.edit_client', compact('client'));
        } catch (\Exception $e) {
            Log::error('Error retrieving Client View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Client View not found.'], 500);
        }
    }

    public function editMarshall($sessionID)
    {
        try {
            // Check if session exists and is not expired
            if (!session()->has($sessionID) || now()->greaterThan(session()->get('session_expiry_' . $sessionID))) {
                return redirect()->route('marshall.list')->with('error', 'Session expired.');
            }

            // Retrieve the client ID from the session
            $userId = session()->get($sessionID);

            // Fetch the client data
            $marshall = Marshall::findOrFail($userId);

            return view('users.edit_marshall', compact('marshall'));
        } catch (\Exception $e) {
            Log::error('Error retrieving Marshall View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Marshall View not found.'], 500);
        }
    }

    public function editInspector($sessionID)
    {
        try {
            // Check if session exists and is not expired
            if (!session()->has($sessionID) || now()->greaterThan(session()->get('session_expiry_' . $sessionID))) {
                return redirect()->route('inspector.list')->with('error', 'Session expired.');
            }

            // Retrieve the inspector ID from the session
            $userId = session()->get($sessionID);

            // Fetch the inspector data
            $inspector = Inspector::findOrFail($userId);

            return view('users.edit_inspector', compact('inspector'));
        } catch (\Exception $e) {
            Log::error('Error retrieving Inspector View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Inspector View not found.'], 500);
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
