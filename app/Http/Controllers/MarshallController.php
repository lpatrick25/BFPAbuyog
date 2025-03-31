<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarshallCreateRequest;
use App\Http\Requests\MarshallUpdateRequest;
use App\Http\Resources\User\PaginatedUserResource;
use App\Http\Resources\User\UserResource;
use App\Models\Marshall;
use App\Services\UserService;
use Illuminate\Http\Request;

class MarshallController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(MarshallCreateRequest $request)
    {
        $user = $this->userService->store($request->validated(), 'Marshall');

        return new UserResource($user);
    }

    public function update(MarshallUpdateRequest $request, $id)
    {
        $user = $this->userService->update($request->validated(), 'Marshall', $id);

        return new UserResource($user);
    }

    public function index(Request $request)
    {
        $query = $this->userService->getAllUser('Marshall');
        $users = $query->paginate($this->limit, ['*'], 'page', $this->page);

        return new PaginatedUserResource($users);
    }

    public function destroy(Marshall $marshall)
    {
        $marshall->delete();
        return response()->json('', 200);
    }
}
