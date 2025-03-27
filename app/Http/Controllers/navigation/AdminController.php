<?php

namespace App\Http\Controllers\navigation;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Inspector;
use App\Models\Marshall;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function dashboards()
    {
        try {
            return view('admin.dashboard');
        } catch (\Exception $e) {
            Log::error('Error retrieving Dashboard View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Dashboard View not found.'], 500);
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
}
