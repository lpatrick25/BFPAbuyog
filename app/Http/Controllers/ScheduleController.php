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

    public function store(ScheduleStoreRequest $request): ScheduleResource
    {
        $schedule = $this->scheduleService->store($request->validated());

        return new ScheduleResource($schedule);
    }

    public function update(ScheduleUpdateRequest $request, $application_id): ScheduleResource
    {
        $schedule = $this->scheduleService->update($request->validated(), $application_id);

        return new ScheduleResource($schedule);
    }

    public function index(): PaginatedScheduleResource
    {
        $query = $this->scheduleService->getAllSchedule();
        $schedules = $query->paginate($this->limit, ['*'], 'page', $this->page);

        return new PaginatedScheduleResource($schedules);
    }
}
