<?php

namespace App\Services;

use App\Models\Schedule;

class ScheduleService
{

    public function getAllSchedule()
    {
        return Schedule::with(['application.establishment', 'inspector']);
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
