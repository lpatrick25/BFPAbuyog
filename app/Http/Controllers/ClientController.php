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
    protected $limit;
    protected $page;

    public function __construct(UserService $userService, Request $request)
    {
        $this->limit = (int) $request->get('limit', 10);
        $this->page = (int) $request->get('page', 1);
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

    public function index()
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
