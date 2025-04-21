<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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

            // Check if the account is active
            if (!$user->is_active) {
                // Log out the user just in case
                Auth::logout();

                return response()->json([
                    'status' => 'error',
                    'message' => 'Account disabled. Please contact the administrator.'
                ], 401);
            }

            // Create a Sanctum token with user agent as the token name
            $token = $user->createToken($request->userAgent() ?? 'api-token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful.',
                'token' => $token,
                'user' => $user->only(['id', 'name', 'email']) // Return only safe fields
            ], 200);
        }

        // Invalid login
        return response()->json([
            'status' => 'error',
            'message' => 'Invalid email or password.'
        ], 401);
    }
}
