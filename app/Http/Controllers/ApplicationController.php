<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationStoreRequest;
use App\Http\Requests\ApplicationUpdateRequest;
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
        return $this->applicationService->storeApplication($request);
    }

    public function update(ApplicationUpdateRequest $request, $id)
    {
        return $this->applicationService->updateApplication($request, $id);
    }

    public function destroy($id)
    {
        return $this->applicationService->deleteApplication($id);
    }

    public function index(Request $request)
    {
        return $this->applicationService->getAllApplications($request);
    }

    public function show($id)
    {
        return $this->applicationService->getApplicationById($id);
    }
}
