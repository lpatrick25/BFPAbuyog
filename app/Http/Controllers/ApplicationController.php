<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationStoreRequest;
use App\Http\Requests\ApplicationUpdateRequest;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\Establishment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    /**
     * Store a new Application.
     */
    public function store(ApplicationStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            // Generate a unique application number
            $applicationNumber = 'FSIC-' . strtoupper(Str::random(10));

            // Ensure it's unique
            while (Application::where('application_number', $applicationNumber)->exists()) {
                $applicationNumber = 'FSIC-' . strtoupper(Str::random(10));
            }

            // Merge application number into request
            $request->merge(['application_number' => $applicationNumber]);

            // Merge application date
            $request->merge(['application_date' => now()]);

            // Create Application
            $application = Application::create($request->all());

            // Fetch required files based on fsic_type
            $fsicType = $application->fsic_type;
            $requirements = config("fsic_requirements.$fsicType", []);

            foreach ($requirements as $requirement) {
                $inputName = Str::snake(str_replace(['(', ')'], '', $requirement)); // Match JavaScript naming
                if ($request->hasFile($inputName)) {
                    $application->addMedia($request->file($inputName))
                        ->usingName($requirement) // Store original name
                        ->toMediaCollection('fsic_requirements');
                }
            }

            DB::commit();
            return response()->json(['message' => 'Application created successfully', 'application' => $application], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Something went wrong.', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Update an Application.
     */
    public function update(ApplicationUpdateRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            // Find the Application
            $application = Application::findOrFail($id);

            $application->clearMediaCollection('fsic_requirements');

            // Fetch required files based on fsic_type
            $fsicType = $application->fsic_type;
            $requirements = config("fsic_requirements.$fsicType", []);

            foreach ($requirements as $requirement) {
                $inputName = Str::snake(str_replace(['(', ')'], '', $requirement)); // Match JavaScript naming

                if ($request->hasFile($inputName)) {
                    // Upload new file
                    $application->addMedia($request->file($inputName))
                        ->usingName($requirement) // Store original name
                        ->toMediaCollection('fsic_requirements');
                }
            }

            DB::commit();
            Log::info('Application updated successfully', ['application_id' => $application->id]);

            return response()->json(['message' => 'Application updated successfully', 'application' => $application], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Application', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete an Application.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // Find Application
            $application = Application::findOrFail($id);

            // Delete Application
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

    /**
     * Get all Applications.
     */
    public function index(Request $request)
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

            // Transform data using API Resource
            return response()->json([
                'total' => $applications->total(),
                'rows' => ApplicationResource::collection($applications),
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching Applications', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Get a single Application.
     */
    public function show($id)
    {
        try {
            $application = Application::findOrFail($id);

            // Attach FSIC requirements
            $fsic_requirements = $application->getMedia('fsic_requirements')->map(function ($media) {
                return [
                    'name' => $media->name,
                    'url' => $media->getUrl(),
                    'thumbnail' => $media->getUrl('thumbnail'),
                ];
            });

            return response()->json([
                'fsic_requirements' => $fsic_requirements
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching Application', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Application not found.'], 404);
        }
    }
}
