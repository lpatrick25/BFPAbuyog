<?php

namespace App\Http\Controllers;

use App\Http\Requests\InspectorCreateRequest;
use App\Http\Requests\InspectorUpdateRequest;
use App\Models\Inspector;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class InspectorController extends Controller
{
    /**
     * Store a new Inspector and associated User.
     */
    public function store(InspectorCreateRequest $request)
    {
        try {
            // Start DB transaction
            DB::beginTransaction();

            // Create User
            $user = User::create([
                'password' => Hash::make($request->password),
                'email' => $request->email,
                'email_verified_at' => now(),
                'role' => 'Inspector',
                'is_active' => true,
            ]);

            // Create Inspector linked to the User
            $inspector = Inspector::create([
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
            Log::info('Inspector created successfully', ['inspector_id' => $inspector->id, 'user_id' => $user->id]);

            // Return success response
            return response()->json(['message' => 'Inspector created successfully', 'inspector' => $inspector], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the full error for debugging
            Log::error('Error creating Inspector', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Update a Inspector and associated User.
     */
    public function update(InspectorUpdateRequest $request, $id)
    {
        try {
            // Start DB transaction
            DB::beginTransaction();

            // Find the Inspector
            $inspector = Inspector::findOrFail($id);

            // Check if the email was changed
            if ($inspector->email !== $request->email) {
                // Find the related user
                $user = User::findOrFail($inspector->user_id);
                $user->update(['email' => $request->email]);
            }

            // Update Inspector
            $inspector->update($request->validated());

            DB::commit();
            Log::info('Inspector updated successfully', ['inspector_id' => $inspector->id]);

            return response()->json(['message' => 'Inspector updated successfully', 'inspector' => $inspector], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Inspector', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Delete a Inspector and associated User.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // Find the Inspector
            $inspector = Inspector::findOrFail($id);
            $user = $inspector->user;

            // Delete Inspector and User
            $inspector->delete();
            $user->delete();

            DB::commit();
            Log::info('Inspector deleted successfully', ['inspector_id' => $id]);

            return response()->json(['message' => 'Inspector deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Inspector', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Get all Inspectors.
     */
    public function index(Request $request)
    {
        try {
            // Get pagination parameters from request
            $limit = $request->get('limit', 10); // Default to 10 records per page
            $page = $request->get('page', 1); // Default to page 1

            // Fetch paginated inspectors
            $inspectors = Inspector::with('user')->paginate($limit, ['*'], 'page', $page);

            // Format response for Bootstrap Table
            return response()->json([
                'total' => $inspectors->total(),    // Total number of records
                'rows' => $inspectors->items(),     // Current page data
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching Inspectors', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Get a single Inspector.
     */
    public function show($id)
    {
        try {
            $inspector = Inspector::with('user')->findOrFail($id);
            return response()->json(['inspector' => $inspector], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching Inspector', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Inspector not found.'], 404);
        }
    }
}
