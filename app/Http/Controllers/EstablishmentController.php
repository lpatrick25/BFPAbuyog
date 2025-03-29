<?php

namespace App\Http\Controllers;

use App\Http\Requests\EstablishmentStoreRequest;
use App\Http\Requests\EstablishmentUpdateRequest;
use App\Http\Resources\Establishment\EstablishmentResource;
use App\Http\Resources\Establishment\PaginatedEstablishmentResource;
use App\Models\Establishment;
use App\Services\EstablishmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EstablishmentController extends Controller
{

    protected $establishmentService;

    public function __construct(EstablishmentService $establishmentService)
    {
        $this->establishmentService = $establishmentService;
    }

    /**
     * Store a new Establishment.
     */
    public function store(EstablishmentStoreRequest $request)
    {
        $establishment = $this->establishmentService->store($request->validated());

        return new EstablishmentResource($establishment);
    }

    /**
     * Update an Establishment.
     */
    public function update(EstablishmentUpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            // Find Establishment
            $establishment = Establishment::findOrFail($id);

            // Update Establishment
            $establishment->update($request->all());

            DB::commit();
            Log::info('Establishment updated successfully', ['establishment_id' => $establishment->id]);

            return response()->json(['message' => 'Establishment updated successfully', 'establishment' => $establishment], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Establishment', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Delete an Establishment.
     */
    public function destroy(Establishment $establishment)
    {
        $establishment->delete();
    }

    /**
     * Get all Establishments.
     */
    public function index(Request $request)
    {
        $query = $this->establishmentService->getAllEstablishments();
        $establishments = $query->paginate($this->limit, ['*'], 'page', $this->page);

        return new PaginatedEstablishmentResource($establishments);
    }

    /**
     * Get a single Establishment.
     */
    public function show(Establishment $establishment)
    {
        return new EstablishmentResource($establishment);
    }
}
