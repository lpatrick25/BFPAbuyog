<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleStoreRequest;
use App\Http\Resources\ScheduleResource;
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
    public function store(ScheduleStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            // Create Schedule
            $schedule = Schedule::create($request->all());

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
    public function index(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);
            $page = $request->get('page', 1);

            $clients = Schedule::with(['application.establishment', 'inspector'])
                ->paginate($limit, ['*'], 'page', $page);

            // Transform data using API Resource
            return response()->json([
                'total' => $clients->total(),
                'rows' => ScheduleResource::collection($clients),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching Clients', ['error' => $e->getMessage()]);
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
