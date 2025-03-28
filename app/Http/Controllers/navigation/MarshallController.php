<?php

namespace App\Http\Controllers\navigation;

use App\Http\Controllers\Controller;
use App\Models\ApplicationStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
}
