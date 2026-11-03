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
    /*
     * Display a listing of the departments.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Actions\SuperAdmin\Department\ListDepartmentsAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ListDepartmentsAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Departments retrieved successfully',
            'data'    => $action->execute($request),
        ]);
    }

    /*
    * Store a newly created department in storage.
    * @param  \App\Http\Requests\SuperAdmin\Department\StoreDepartmentRequest  $request
    * @param  \App\Actions\SuperAdmin\Department\CreateDepartmentAction  $action
    */
    public function store(StoreDepartmentRequest $request, CreateDepartmentAction $action): JsonResponse
    {
        $department = $action->execute($request->validated());

        return response()->json([
            'message' => 'Department created successfully',
            'data'    => $department->load('faculty:id,name,code'),
        ], 201);
    }


    /*
     * Display the specified department.
     *
     * @param  \App\Models\Department  $department
     * @param  \App\Actions\SuperAdmin\Department\ShowDepartmentAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Department $department, ShowDepartmentAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Department retrieved successfully',
            'data'    => $action->execute($department),
        ]);
    }


    /*
     * Update the specified department in storage.
     *
     * @param  \App\Http\Requests\SuperAdmin\Department\UpdateDepartmentRequest  $request
     * @param  \App\Models\Department  $department
     * @param  \App\Actions\SuperAdmin\Department\UpdateDepartmentAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateDepartmentRequest $request, Department $department, UpdateDepartmentAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Department updated successfully',
            'data'    => $action->execute($department, $request->validated()),
        ]);
    }


/*
     * Remove the specified department from storage.
     *
     * @param  \App\Models\Department  $department
     * @param  \App\Actions\SuperAdmin\Department\DeleteDepartmentAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Department $department, DeleteDepartmentAction $action): JsonResponse
    {
        $action->execute($department);

        return response()->json(['message' => 'Department deleted successfully']);
    }
}
