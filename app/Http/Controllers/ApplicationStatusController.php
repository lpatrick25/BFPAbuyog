<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationStatusRequest;
use App\Models\ApplicationStatus;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Mail\ApplicationStatusNotification;
use Illuminate\Support\Facades\Mail;

class ApplicationStatusController extends Controller
{
    /**
     * Store a new Application Status.
     */
    public function store(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'status' => 'required|string|max:50',
            'remarks' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Create Application Status
            $status = ApplicationStatus::create([
                'application_id' => $validated['application_id'],
                'status' => $validated['status'],
                'remarks' => $validated['remarks'],
            ]);

            DB::commit();
            Log::info('Application Status created successfully', ['status_id' => $status->id]);

            return response()->json(['message' => 'Application status added successfully', 'status' => $status], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding Application Status', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Update an Application Status.
     */
    public function update(ApplicationStatusRequest $request, $application_id)
    {
        try {
            DB::beginTransaction();

            $applicationStatus = ApplicationStatus::where('application_id', $application_id)
                ->where('status', $request->status)
                ->whereNull('remarks')->first();

            $applicationStatus->remarks = $request->remarks;
            $applicationStatus->save();

            ApplicationStatus::create([
                'application_id' => $application_id,
                'status' => $request->status,
            ]);

            DB::commit();
            Log::info('Application Status updated', ['application_id' => $application_id]);

            return response()->json(['message' => 'Application status updated', 'applicationStatus' => $applicationStatus], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Application Status', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Delete an Application Status.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // Find Application Status
            $status = ApplicationStatus::findOrFail($id);

            // Delete Status
            $status->delete();

            DB::commit();
            Log::info('Application Status deleted successfully', ['status_id' => $id]);

            return response()->json(['message' => 'Application status deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Application Status', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Get all Application Statuses.
     */
    public function index($application_id)
    {
        try {
            $statuses = ApplicationStatus::where('application_id', $application_id)->get();
            return response()->json(['statuses' => $statuses], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching Application Statuses', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Get a single Application Status.
     */
    public function show($application_id)
    {
        try {
            $status = ApplicationStatus::where('application_id', $application_id)->get();
            return response()->json(['status' => $status], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching Application Status', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Application status not found.'], 404);
        }
    }
}
