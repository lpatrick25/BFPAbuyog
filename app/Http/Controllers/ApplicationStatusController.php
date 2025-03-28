<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationStatusRequest;
use App\Models\ApplicationStatus;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApplicationStatusController extends Controller
{
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

            if ($request->has('schedule_date')) {
                $schedule = Schedule::where('application_id', $application_id)->first();
                $schedule->schedule_date = $request->schedule_date;
                $schedule->save();
            }

            DB::commit();
            Log::info('Application Status updated', ['application_id' => $application_id]);

            return response()->json(['message' => 'Application status updated'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Application Status', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
}
