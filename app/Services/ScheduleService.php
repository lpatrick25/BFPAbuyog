<?php

namespace App\Services;

use App\Models\Schedule;

class ScheduleService
{

    public function getAllSchedule()
    {
        $schedule = Schedule::with(['application.establishment', 'inspector']);

        if (auth()->user()->role === 'Inspector') {
            $inspector = auth()->user()->inspector;

            if ($inspector) {
                $schedule->where('inspector_id', $inspector->id)
                    ->where('status', 'Ongoing');
            }
        }

        if (auth()->user()->role === 'Client') {
            $client = auth()->user()->client;

            if ($client) {
                $schedules = Schedule::whereHas('application', function ($query) use ($client) {
                    $query->whereHas('establishment', function ($q) use ($client) {
                        $q->where('client_id', $client->id);
                    });
                })->get();
            }
        }

        return $schedule;
    }

    public function store(array $data)
    {
        $schedule = Schedule::create($data);

        return $schedule;
    }

    public function update(array $data, $application_id)
    {
        $schedule = Schedule::where('application_id', $application_id)->first();
        $schedule->update($data);

        return $schedule;
    }
}
