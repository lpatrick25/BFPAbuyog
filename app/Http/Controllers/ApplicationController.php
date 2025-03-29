<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationStoreRequest;
use App\Http\Requests\ApplicationUpdateRequest;
use App\Http\Resources\Application\ApplicationResource;
use App\Http\Resources\Application\PaginatedApplicationResource;
use App\Models\Application;
use App\Services\ApplicationService;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    protected $applicationService;

    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }

    public function store(ApplicationStoreRequest $request)
    {
        $application = $this->applicationService->store($request->validated());

        return new ApplicationResource($application);
    }

    public function update(ApplicationUpdateRequest $request, $id)
    {
        $application = $this->applicationService->update($request->validated(), $id);

        return new ApplicationResource($application);
    }

    public function destroy(Application $application)
    {
        $application->delete();

        return response()->json('', 200);
    }

    public function index()
    {
        $query = $this->applicationService->getAllApplications();
        $applications = $query->paginate($this->limit, ['*'], 'page', $this->page);

        return new PaginatedApplicationResource($applications);
    }
}
