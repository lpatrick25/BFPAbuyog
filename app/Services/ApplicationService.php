<?php

namespace App\Services;

use App\Models\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApplicationService
{
    public function store(array $data)
    {
        try {
            DB::beginTransaction();
            $applicationNumber = $this->generateUniqueApplicationNumber();

            $data['application_number'] = $applicationNumber;
            $data['application_date'] = now();
            $application = Application::create($data);

            $this->handleFsicRequirements($application);

            DB::commit();

            return $application;
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    public function update(array $array, $id)
    {
        try {
            DB::beginTransaction();

            $application = Application::find($id);
            $application->clearMediaCollection('fsic_requirements');
            $this->handleFsicRequirements($application);

            DB::commit();

            return $application;
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    private function handleFsicRequirements(Application $application)
    {
        $fsicType = $application->fsic_type;
        $requirements = config("fsic_requirements.$fsicType", []);

        foreach ($requirements as $requirement) {
            $inputName = Str::snake(str_replace(['(', ')'], '', $requirement));

            // Use Laravel's global request() helper to check for files
            if (request()->hasFile($inputName)) {
                $application->addMedia(request()->file($inputName))
                    ->usingName($requirement)
                    ->toMediaCollection('fsic_requirements');
            }
        }
    }

    public function getAllApplications()
    {
        $user = auth()->user();
        $clientId = optional($user->client)->id;

        if (!$clientId) {
            return Application::with(['establishment', 'applicationStatuses']);
        }

        return Application::with(['establishment', 'applicationStatuses'])
            ->whereHas('establishment', function ($query) use ($clientId) {
                $query->where('client_id', $clientId);
            });
    }

    private function generateUniqueApplicationNumber()
    {
        do {
            $applicationNumber = 'FSIC-' . strtoupper(Str::random(10));
        } while (Application::where('application_number', $applicationNumber)->exists());

        return $applicationNumber;
    }
}
