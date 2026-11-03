<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Actions\SuperAdmin\Department\CreateDepartmentAction;
use App\Actions\SuperAdmin\Department\DeleteDepartmentAction;
use App\Actions\SuperAdmin\Department\ListDepartmentsAction;
use App\Actions\SuperAdmin\Department\ShowDepartmentAction;
use App\Actions\SuperAdmin\Department\UpdateDepartmentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\Department\StoreDepartmentRequest;
use App\Http\Requests\SuperAdmin\Department\UpdateDepartmentRequest;
use App\Models\Department;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index(Request $request, ListDepartmentsAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Departments retrieved successfully',
            'data'    => $action->execute($request),
        ]);
    }

    public function store(StoreDepartmentRequest $request, CreateDepartmentAction $action): JsonResponse
    {
        $department = $action->execute($request->validated());

        return response()->json([
            'message' => 'Department created successfully',
            'data'    => $department->load('faculty:id,name,code'),
        ], 201);
    }

    public function show(Department $department, ShowDepartmentAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Department retrieved successfully',
            'data'    => $action->execute($department),
        ]);
    }

    public function update(UpdateDepartmentRequest $request, Department $department, UpdateDepartmentAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Department updated successfully',
            'data'    => $action->execute($department, $request->validated()),
        ]);
    }

    public function destroy(Department $department, DeleteDepartmentAction $action): JsonResponse
    {
        $action->execute($department);

        return response()->json(['message' => 'Department deleted successfully']);
    }
}
