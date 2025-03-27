<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\Client;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RegistrationController extends Controller
{
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = User::create([
                'password' => Hash::make($request->password),
                'email' => $request->email,
                'role' => 'Client',
                'is_active' => true,
            ]);

            $client = Client::create([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'extension_name' => $request->extension_name,
                'contact_number' => $request->contact_number,
                'email' => $request->email,
                'user_id' => $user->id,
            ]);

            DB::commit();
            Log::info('Client created successfully', ['client_id' => $client->id]);

            event(new Registered($user)); // Trigger verification email

            return response()->json(['message' => 'Account registration success', 'account' => $client], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating Client', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }
}
