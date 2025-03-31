<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientCreateRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Http\Resources\User\PaginatedUserResource;
use App\Http\Resources\User\UserResource;
use App\Models\Client;
use App\Services\UserService;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(ClientCreateRequest $request)
    {
        $user = $this->userService->store($request->validated(), 'Client');

        return new UserResource($user);
    }

    public function update(ClientUpdateRequest $request, $id)
    {
        $user = $this->userService->update($request->validated(), 'Client', $id);

        return new UserResource($user);
    }

    public function index(Request $request)
    {
        $query = $this->userService->getAllUser('Client');
        $users = $query->paginate($this->limit, ['*'], 'page', $this->page);

        return new PaginatedUserResource($users);
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return response()->json('', 200);
    }
}
