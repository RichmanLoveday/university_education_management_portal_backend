<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Actions\SuperAdmin\DepartmentAdmin\CreateDepartmentAdminAction;
use App\Actions\SuperAdmin\DepartmentAdmin\DeleteDepartmentAdminAction;
use App\Actions\SuperAdmin\DepartmentAdmin\ListDepartmentAdminsAction;
use App\Actions\SuperAdmin\DepartmentAdmin\UpdateDepartmentAdminAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\DepartmentAdmin\StoreDepartmentAdminRequest;
use App\Http\Requests\SuperAdmin\DepartmentAdmin\UpdateDepartmentAdminRequest;
use App\Models\DepartmentAdmin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentAdminController extends Controller
{
    public function index(Request $request, ListDepartmentAdminsAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Department administrators retrieved successfully',
            'data'    => $action->execute($request),
        ]);
    }

    public function store(StoreDepartmentAdminRequest $request, CreateDepartmentAdminAction $action): JsonResponse
    {
        $departmentAdmin = $action->execute($request->validated());

        return response()->json([
            'message' => 'Department administrator created successfully',
            'data'    => $departmentAdmin->load(['user:id,name,email,is_active', 'department:id,name,code']),
        ], 201);
    }

    public function show(DepartmentAdmin $department_admin): JsonResponse
    {
        return response()->json([
            'message' => 'Department administrator retrieved successfully',
            'data'    => $department_admin->load(['user:id,name,email,is_active,last_login_at', 'department:id,name,code']),
        ]);
    }

    public function update(UpdateDepartmentAdminRequest $request, DepartmentAdmin $department_admin, UpdateDepartmentAdminAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Department administrator updated successfully',
            'data'    => $action->execute($department_admin, $request->validated()),
        ]);
    }

    public function destroy(DepartmentAdmin $department_admin, DeleteDepartmentAdminAction $action): JsonResponse
    {
        $action->execute($department_admin);

        return response()->json(['message' => 'Department administrator deleted successfully']);
    }
}
