<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Actions\SuperAdmin\User\CreateUserAction;
use App\Actions\SuperAdmin\User\ListUsersAction;
use App\Actions\SuperAdmin\User\ShowUserAction;
use App\Actions\SuperAdmin\User\UpdateUserAction;
use App\Actions\SuperAdmin\User\UpdateUserRoleAction;
use App\Actions\SuperAdmin\User\UpdateUserStatusAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\User\StoreUserRequest;
use App\Http\Requests\SuperAdmin\User\UpdateUserRequest;
use App\Http\Requests\SuperAdmin\User\UpdateUserRoleRequest;
use App\Http\Requests\SuperAdmin\User\UpdateUserStatusRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request, ListUsersAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Users retrieved successfully',
            'data'    => $action->execute($request),
        ]);
    }

    public function store(StoreUserRequest $request, CreateUserAction $action): JsonResponse
    {
        $user = $action->execute($request->validated());

        return response()->json([
            'message' => 'User created successfully',
            'data'    => $user,
        ], 201);
    }

    public function show(User $user, ShowUserAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'User retrieved successfully',
            'data'    => $action->execute($user),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user, UpdateUserAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'User updated successfully',
            'data'    => $action->execute($user, $request->validated()),
        ]);
    }

    public function updateStatus(UpdateUserStatusRequest $request, User $user, UpdateUserStatusAction $action): JsonResponse
    {
        $user = $action->execute($user, $request->boolean('is_active'));

        return response()->json([
            'message' => 'User status updated successfully',
            'data'    => $user,
        ]);
    }

    public function updateRole(UpdateUserRoleRequest $request, User $user, UpdateUserRoleAction $action): JsonResponse
    {
        $user = $action->execute($user, $request->validated('role'));

        return response()->json([
            'message' => 'User role updated successfully',
            'data'    => $user,
        ]);
    }
}