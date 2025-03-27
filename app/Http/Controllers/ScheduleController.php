<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Application;
use App\Models\Inspector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    /**
     * Store a new Schedule.
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'inspector_id' => 'required|exists:inspectors,id',
            'schedule_date' => 'required|date|after_or_equal:today',
        ]);

        DB::beginTransaction();
        try {
            // Create Schedule
            $schedule = Schedule::create($validated);

            DB::commit();
            Log::info('Schedule created successfully', ['schedule_id' => $schedule->id]);

            return response()->json(['message' => 'Schedule created successfully', 'schedule' => $schedule], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Schedule', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Update a Schedule.
     */
    public function update(Request $request, $id)
    {
        // Validate request
        $validated = $request->validate([
            'schedule_date' => 'required|date|after_or_equal:today',
        ]);

        DB::beginTransaction();
        try {
            // Find Schedule
            $schedule = Schedule::findOrFail($id);

            // Update Schedule
            $schedule->update($validated);

            DB::commit();
            Log::info('Schedule updated successfully', ['schedule_id' => $schedule->id]);

            return response()->json(['message' => 'Schedule updated successfully', 'schedule' => $schedule], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Schedule', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Delete a Schedule.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // Find Schedule
            $schedule = Schedule::findOrFail($id);

            // Delete Schedule
            $schedule->delete();

            DB::commit();
            Log::info('Schedule deleted successfully', ['schedule_id' => $id]);

            return response()->json(['message' => 'Schedule deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Schedule', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Get all Schedules.
     */
    public function index()
    {
        try {
            $schedules = Schedule::with(['application', 'inspector'])->get();
            return response()->json(['schedules' => $schedules], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching Schedules', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Get a single Schedule.
     */
    public function show($id)
    {
        try {
            $schedule = Schedule::with(['application', 'inspector'])->findOrFail($id);
            return response()->json(['schedule' => $schedule], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching Schedule', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Schedule not found.'], 404);
        }
    }
}
