<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Establishment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApplicationService
{
    public function storeApplication(Request $request)
    {
        try {
            DB::beginTransaction();

            $applicationNumber = $this->generateUniqueApplicationNumber();

            $request->merge([
                'application_number' => $applicationNumber,
                'application_date' => now(),
            ]);

            $application = Application::create($request->all());

            $this->handleFsicRequirements($application, $request);

            DB::commit();
            return response()->json(['message' => 'Application created successfully', 'application' => $application], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong.', 'details' => $e->getMessage()], 500);
        }
    }

    public function updateApplication(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $application = Application::findOrFail($id);
            $application->clearMediaCollection('fsic_requirements');

            $this->handleFsicRequirements($application, $request);

            DB::commit();
            Log::info('Application updated successfully', ['application_id' => $application->id]);

            return response()->json(['message' => 'Application updated successfully', 'application' => $application], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Application', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.', 'details' => $e->getMessage()], 500);
        }
    }

    public function deleteApplication($id)
    {
        try {
            DB::beginTransaction();

            $application = Application::findOrFail($id);
            $application->delete();

            DB::commit();
            Log::info('Application deleted successfully', ['application_id' => $id]);

            return response()->json(['message' => 'Application deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Application', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    public function getAllApplications(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);
            $page = $request->get('page', 1);

            if (auth()->user()->role === 'Marshall') {
                $applications = Application::with(['establishment', 'applicationStatuses'])
                    ->paginate($limit, ['*'], 'page', $page);
            } else {
                $client_id = optional(auth()->user())->client->id;
                $establishment = Establishment::where('client_id', $client_id)->first();

                if (!$establishment) {
                    return response()->json(['error' => 'Establishment not found.'], 404);
                }

                $applications = Application::with(['establishment', 'applicationStatuses'])
                    ->where('establishment_id', $establishment->id)
                    ->paginate($limit, ['*'], 'page', $page);
            }

            return response()->json([
                'total' => $applications->total(),
                'rows' => $applications->items(),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching Applications', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    public function getApplicationById($id)
    {
        try {
            $application = Application::findOrFail($id);

            $fsic_requirements = $application->getMedia('fsic_requirements')->map(function ($media) {
                return [
                    'name' => $media->name,
                    'url' => $media->getUrl(),
                    'thumbnail' => $media->getUrl('thumbnail'),
                ];
            });

            return response()->json(['fsic_requirements' => $fsic_requirements], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching Application', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Application not found.'], 404);
        }
    }

    private function generateUniqueApplicationNumber()
    {
        do {
            $applicationNumber = 'FSIC-' . strtoupper(Str::random(10));
        } while (Application::where('application_number', $applicationNumber)->exists());

        return $applicationNumber;
    }

    private function handleFsicRequirements(Application $application, Request $request)
    {
        $fsicType = $application->fsic_type;
        $requirements = config("fsic_requirements.$fsicType", []);

        foreach ($requirements as $requirement) {
            $inputName = Str::snake(str_replace(['(', ')'], '', $requirement));

            if ($request->hasFile($inputName)) {
                $application->addMedia($request->file($inputName))
                    ->usingName($requirement)
                    ->toMediaCollection('fsic_requirements');
            }
        }
    }
}
