<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationStatusRequest;
use App\Services\ApplicationStatusService;
use Illuminate\Support\Facades\Log;

class ApplicationStatusController extends Controller
{
    protected $applicationStatusService;

    public function __construct(ApplicationStatusService $applicationStatusService)
    {
        $this->applicationStatusService = $applicationStatusService;
    }

    public function update(ApplicationStatusRequest $request, $application_id)
    {
        return $this->applicationStatusService->updateApplicationStatus($request, $application_id);
    }
}
