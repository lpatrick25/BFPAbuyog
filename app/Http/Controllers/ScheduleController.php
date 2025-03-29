<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleStoreRequest;
use App\Http\Requests\ScheduleUpdateRequest;
use App\Http\Resources\Schedule\PaginatedScheduleResource;
use App\Http\Resources\Schedule\ScheduleResource;
use App\Services\ScheduleService;

class ScheduleController extends Controller
{

    protected $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    /**
     * Store a new Schedule.
     */
    public function store(ScheduleStoreRequest $request)
    {
        $schedule = $this->scheduleService->store($request->validated());

        return new ScheduleResource($schedule);
    }

    /**
     * Update a Schedule.
     */
    public function update(ScheduleUpdateRequest $request, $application_id)
    {
        $schedule = $this->scheduleService->update($request->validated(), $application_id);

        return new ScheduleResource($schedule);
    }

    /**
     * Get all Schedules.
     */
    public function index()
    {
        $query = $this->scheduleService->getAllSchedule();
        $schedules = $query->paginate($this->limit, ['*'], 'page', $this->page);

        return new PaginatedScheduleResource($schedules);
    }
}
