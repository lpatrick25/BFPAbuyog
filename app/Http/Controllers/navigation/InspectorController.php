<?php

namespace App\Http\Controllers\navigation;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class InspectorController extends Controller
{
    public function dashboards()
    {
        try {

            return view('inspector.dashboard');
        } catch (\Exception $e) {
            Log::error('Error retrieving Dashboard View', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Dashboard View not found.'], 500);
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
}
