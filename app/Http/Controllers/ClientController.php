<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientCreateRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ClientController extends Controller
{
    /**
     * Store a new Client and associated User.
     */
    public function store(ClientCreateRequest $request)
    {
        try {
            // Start DB transaction
            DB::beginTransaction();

            // Create User
            $user = User::create([
                'password' => Hash::make($request->password),
                'email' => $request->email,
                'email_verified_at' => now(),
                'role' => 'Client',
                'is_active' => true,
            ]);

            // Create Client linked to the User
            $client = Client::create([
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
            Log::info('Client created successfully', ['client_id' => $client->id, 'user_id' => $user->id]);

            // Return success response
            return response()->json(['message' => 'Client created successfully', 'client' => $client], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            // Log the full error for debugging
            Log::error('Error creating Client', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Update a Client and associated User.
     */
    public function update(ClientUpdateRequest $request, $id)
    {
        try {
            // Start DB transaction
            DB::beginTransaction();

            // Find the Client
            $client = Client::findOrFail($id);

            // Check if the email was changed
            if ($client->email !== $request->email) {
                // Find the related user
                $user = User::findOrFail($client->user_id);
                $user->update(['email' => $request->email]);
            }

            // Update Client
            $client->update($request->validated());

            DB::commit();
            Log::info('Client updated successfully', ['client_id' => $client->id]);

            return response()->json(['message' => 'Client updated successfully', 'client' => $client], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating Client', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Delete a Client and associated User.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            // Find the Client
            $client = Client::findOrFail($id);
            $user = $client->user;

            // Delete Client and User
            $client->delete();
            $user->delete();

            DB::commit();
            Log::info('Client deleted successfully', ['client_id' => $id]);

            return response()->json(['message' => 'Client deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting Client', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Get all Clients.
     */
    public function index(Request $request)
    {
        try {
            // Get pagination parameters from request
            $limit = $request->get('limit', 10); // Default to 10 records per page
            $page = $request->get('page', 1); // Default to page 1

            // Fetch paginated clients
            $clients = Client::with('user')->paginate($limit, ['*'], 'page', $page);

            // Format response for Bootstrap Table
            return response()->json([
                'total' => $clients->total(),    // Total number of records
                'rows' => $clients->items(),     // Current page data
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching Clients', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Get a single Client.
     */
    public function show($id)
    {
        try {
            $client = Client::with('user')->findOrFail($id);
            return response()->json(['client' => $client], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching Client', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Client not found.'], 404);
        }
    }
}
