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
    public function store(array $data)
    {
        $applicationNumber = $this->generateUniqueApplicationNumber();

        $data['application_number'] = $applicationNumber;
        $data['application_date'] = now();
        $application = Application::create($data);

        $this->handleFsicRequirements($application);

        return $application;
    }

    public function update(array $array, $id)
    {
        $application = Application::find($id);
        $application->clearMediaCollection('fsic_requirements');
        $this->handleFsicRequirements($application);

        return $application;
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
        if (auth()->user()->role === 'Marshall') {
            return Application::with(['establishment', 'applicationStatuses']);
        } else {
            $client_id = optional(auth()->user())->client->id;
            $establishment = Establishment::where('client_id', $client_id)->first();

            if (!$establishment) {
                return Application::whereNull('id');
            }

            return Application::with(['establishment', 'applicationStatuses'])
                ->where('establishment_id', $establishment->id);
        }
    }

    private function generateUniqueApplicationNumber()
    {
        do {
            $applicationNumber = 'FSIC-' . strtoupper(Str::random(10));
        } while (Application::where('application_number', $applicationNumber)->exists());

        return $applicationNumber;
    }
}
