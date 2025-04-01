<?php

namespace App\Http\Controllers;

use App\Http\Requests\InspectorCreateRequest;
use App\Http\Requests\InspectorUpdateRequest;
use App\Http\Resources\User\PaginatedUserResource;
use App\Http\Resources\User\UserResource;
use App\Models\Inspector;
use App\Services\UserService;
use Illuminate\Http\Request;

class InspectorController extends Controller
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

    public function store(InspectorCreateRequest $request)
    {
        $user = $this->userService->store($request->validated(), 'Inspector');

        return new UserResource($user);
    }

    public function update(InspectorUpdateRequest $request, $id)
    {
        $user = $this->userService->update($request->validated(), 'Inspector', $id);

        return new UserResource($user);
    }

    public function index()
    {
        $query = $this->userService->getAllUser('Inspector');
        $users = $query->paginate($this->limit, ['*'], 'page', $this->page);

        return new PaginatedUserResource($users);
    }

    public function destroy(Inspector $inspector)
    {
        $inspector->delete();
        return response()->json('', 200);
    }
}
