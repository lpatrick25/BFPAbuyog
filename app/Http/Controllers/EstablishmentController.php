<?php

namespace App\Http\Controllers;

use App\Http\Requests\EstablishmentStoreRequest;
use App\Http\Requests\EstablishmentUpdateRequest;
use App\Http\Resources\EstablishmentResource;
use App\Models\Establishment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EstablishmentController extends Controller
{
    /**
     * Store a new Establishment.
     */
    public function store(EstablishmentStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            // Get authenticated user
            $user = auth()->user();

            if (!$user) {
                return response()->json(['error' => 'Unauthorized access.'], 401);
            }

            // Ensure the user has a client record
            if (!$user->client) {
                return response()->json(['error' => 'Client record not found for this user.'], 400);
            }

            // Merge client_id into request
            $request->merge(['client_id' => $user->client->id]);

            // Log the client_id
            Log::info('Creating Establishment for Client ID: ' . $user->client->id);

            // Create the Establishment
            $establishment = Establishment::create($request->all());

            DB::commit();

            return response()->json([
                'message' => 'Establishment created successfully',
                'establishment' => $establishment
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Establishment', ['error' => $e->getMessage()]);

            return response()->json([
                'error' => config('app.debug') ? $e->getMessage() : 'Something went wrong.'
            ], 500);
        }
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
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // Find Establishment
            $establishment = Establishment::findOrFail($id);

            // Delete Establishment
            $establishment->delete();

            DB::commit();
            Log::info('Establishment deleted successfully', ['establishment_id' => $id]);

            return response()->json(['message' => 'Establishment deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Establishment', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Get all Establishments.
     */
    public function index(Request $request)
    {
        try {
            $limit = $request->get('limit', 10);
            $page = $request->get('page', 1);

            // Fetch paginated establishments with client relationship
            $establishments = Establishment::with('client')->paginate($limit, ['*'], 'page', $page);

            // Use Resource for formatting
            return response()->json([
                'total' => $establishments->total(),
                'rows' => EstablishmentResource::collection($establishments->items()), // Apply resource
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching Establishments', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Get a single Establishment.
     */
    public function show($id)
    {
        try {
            $establishment = Establishment::with('client')->findOrFail($id);
            return response()->json(['establishment' => $establishment], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching Establishment', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Establishment not found.'], 404);
        }
    }
}
