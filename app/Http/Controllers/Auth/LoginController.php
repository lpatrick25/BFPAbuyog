<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Attempt login
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            // Create Sanctum token with expiration (e.g., 24 hours)
            $expiresAt = Carbon::now()->addDays(7);
            $tokenResult = $user->createToken('api-token', ['*'], $expiresAt);
            $token = $tokenResult->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'token' => $token,
                'expires_at' => $expiresAt->toISOString(), // ISO 8601 format
                'user' => $user
            ], 200);
        }

        // If login fails
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid email or password.'
        ], 401);
    }
}
