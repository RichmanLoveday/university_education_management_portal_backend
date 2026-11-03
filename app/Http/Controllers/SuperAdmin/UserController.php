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
    /*
     * Display a listing of the users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Actions\SuperAdmin\User\ListUsersAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ListUsersAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Users retrieved successfully',
            'data'    => $action->execute($request),
        ]);
    }

    /*
     * Store a newly created user in storage.
     *
     * @param  \App\Http\Requests\SuperAdmin\User\StoreUserRequest  $request
     * @param  \App\Actions\SuperAdmin\User\CreateUserAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreUserRequest $request, CreateUserAction $action): JsonResponse
    {
        $user = $action->execute($request->validated());

        return response()->json([
            'message' => 'User created successfully',
            'data'    => $user,
        ], 201);
    }

    /*
     * Display the specified user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Actions\SuperAdmin\User\ShowUserAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user, ShowUserAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'User retrieved successfully',
            'data'    => $action->execute($user),
        ]);
    }

    /*
     * Update the specified user in storage.
     *
     * @param  \App\Http\Requests\SuperAdmin\User\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @param  \App\Actions\SuperAdmin\User\UpdateUserAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user, UpdateUserAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'User updated successfully',
            'data'    => $action->execute($user, $request->validated()),
        ]);
    }

    /*
     * Update the specified user's status in storage.
     *
     * @param  \App\Http\Requests\SuperAdmin\User\UpdateUserStatusRequest  $request
     * @param  \App\Models\User  $user
     * @param  \App\Actions\SuperAdmin\User\UpdateUserStatusAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(UpdateUserStatusRequest $request, User $user, UpdateUserStatusAction $action): JsonResponse
    {
        $user = $action->execute($user, $request->boolean('is_active'));

        return response()->json([
            'message' => 'User status updated successfully',
            'data'    => $user,
        ]);
    }

    /*
     * Update the specified user's role in storage.
     *
     * @param  \App\Http\Requests\SuperAdmin\User\UpdateUserRoleRequest  $request
     * @param  \App\Models\User  $user
     * @param  \App\Actions\SuperAdmin\User\UpdateUserRoleAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRole(UpdateUserRoleRequest $request, User $user, UpdateUserRoleAction $action): JsonResponse
    {
        $user = $action->execute($user, $request->validated('role'));

        return response()->json([
            'message' => 'User role updated successfully',
            'data'    => $user,
        ]);
    }
}