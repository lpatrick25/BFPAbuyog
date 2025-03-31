<?php

namespace App\Http\Controllers;

use App\Http\Requests\EstablishmentStoreRequest;
use App\Http\Requests\EstablishmentUpdateRequest;
use App\Http\Resources\Establishment\EstablishmentResource;
use App\Http\Resources\Establishment\PaginatedEstablishmentResource;
use App\Models\Establishment;
use App\Services\EstablishmentService;

class EstablishmentController extends Controller
{

    protected $establishmentService;

    public function __construct(EstablishmentService $establishmentService)
    {
        $this->establishmentService = $establishmentService;
    }

    public function store(EstablishmentStoreRequest $request): EstablishmentResource
    {
        $establishment = $this->establishmentService->store($request->validated());

        return new EstablishmentResource($establishment);
    }

    public function update(EstablishmentUpdateRequest $request, $id): EstablishmentResource
    {
        $establishment = $this->establishmentService->update($request->validated(), $id);

        return new EstablishmentResource($establishment);
    }

    public function destroy(Establishment $establishment)
    {
        $establishment->delete();
        return response()->json('', 200);
    }

    public function index(): PaginatedEstablishmentResource
    {
        $query = $this->establishmentService->getAllEstablishments();
        $establishments = $query->paginate($this->limit, ['*'], 'page', $this->page);

        return new PaginatedEstablishmentResource($establishments);
    }

    public function show(Establishment $establishment): EstablishmentResource
    {
        return new EstablishmentResource($establishment);
    }
}
