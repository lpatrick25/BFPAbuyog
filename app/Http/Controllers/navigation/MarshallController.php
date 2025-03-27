<?php

namespace App\Http\Controllers\navigation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MarshallController extends Controller
{

    public function dashboards()
    {
        try {
            $notifications = auth()->user()->unreadNotifications;

            return view('marshall.dashboard', compact('notifications'));
        } catch (\Exception $e) {
            Log::error('Error retrieving Dashboard View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Dashboard View not found.'], 500);
        }
    }
}
