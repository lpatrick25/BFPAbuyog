<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        switch ($user->role) {
            case 'Client':
                $client = $user->client;
                if ($client) {
                    $client->update($request->all());
                    return new UserResource($client);
                }
                break;
            case 'Marshall':
                $marshall = $user->marshall;
                if ($marshall) {
                    $marshall->update($request->all());
                    return new UserResource($marshall);
                }
                break;
            case 'Inspector':
                $inspector = $user->inspector;
                if ($inspector) {
                    $inspector->update($request->all());
                    return new UserResource($inspector);
                }
                break;
            default:
                return redirect()->back()->with('error', 'Profile update failed. User role not recognized.');
        }
    }
}
