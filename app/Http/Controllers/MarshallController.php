<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarshallCreateRequest;
use App\Http\Requests\MarshallUpdateRequest;
use App\Models\Marshall;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class MarshallController extends Controller
{
    /**
     * Store a new Marshall and associated User.
     */
    public function store(MarshallCreateRequest $request)
    {
        try {
            // Start DB transaction
            DB::beginTransaction();

            // Create User
            $user = User::create([
                'password' => Hash::make($request->password),
                'email' => $request->email,
                'email_verified_at' => now(),
                'role' => 'Marshall',
                'is_active' => true,
            ]);

            // Create Marshall linked to the User
            $marshall = Marshall::create([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name ?? null, // Handle nullable fields
                'last_name' => $request->last_name,
                'extension_name' => $request->extension_name ?? null,
                'contact_number' => $request->contact_number,
                'email' => $request->email,
                'user_id' => $user->id,
            ]);

            // Commit transaction
            DB::commit();

            // Log success
            Log::info('Marshall created successfully', ['marshall_id' => $marshall->id, 'user_id' => $user->id]);

            // Return success response
            return response()->json(['message' => 'Marshall created successfully', 'marshall' => $marshall], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the full error for debugging
            Log::error('Error creating Marshall', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Update a Marshall and associated User.
     */
    public function update(MarshallUpdateRequest $request, $id)
    {
        try {
            // Start DB transaction
            DB::beginTransaction();

            // Find the Marshall
            $marshall = Marshall::findOrFail($id);

            // Check if the email was changed
            if ($marshall->email !== $request->email) {
                // Find the related user
                $user = User::findOrFail($marshall->user_id);
                $user->update(['email' => $request->email]);
            }

            // Update Marshall
            $marshall->update($request->validated());

            DB::commit();
            Log::info('Marshall updated successfully', ['marshall_id' => $marshall->id]);

            return response()->json(['message' => 'Marshall updated successfully', 'marshall' => $marshall], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Marshall', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Delete a Marshall and associated User.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // Find the Marshall
            $marshall = Marshall::findOrFail($id);
            $user = $marshall->user;

            // Delete Marshall and User
            $marshall->delete();
            $user->delete();

            DB::commit();
            Log::info('Marshall deleted successfully', ['marshall_id' => $id]);

            return response()->json(['message' => 'Marshall deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Marshall', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Get all Marshalls.
     */
    public function index(Request $request)
    {
        try {
            // Get pagination parameters from request
            $limit = $request->get('limit', 10); // Default to 10 records per page
            $page = $request->get('page', 1); // Default to page 1

            // Fetch paginated marshalls
            $marshalls = Marshall::with('user')->paginate($limit, ['*'], 'page', $page);

            // Format response for Bootstrap Table
            return response()->json([
                'total' => $marshalls->total(),    // Total number of records
                'rows' => $marshalls->items(),     // Current page data
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching Marshalls', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Get a single Marshall.
     */
    public function show($id)
    {
        try {
            $marshall = Marshall::with('user')->findOrFail($id);
            return response()->json(['marshall' => $marshall], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching Marshall', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Marshall not found.'], 404);
        }
    }
}
