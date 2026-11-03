<?php

namespace App\Http\Controllers\DepartmentAdmin;

use App\Actions\DepartmentAdmin\Lecturer\CreateLecturerAction;
use App\Actions\DepartmentAdmin\Lecturer\DeleteLecturerAction;
use App\Actions\DepartmentAdmin\Lecturer\ShowLecturerAction;
use App\Actions\DepartmentAdmin\Lecturer\ListLecturersAction;
use App\Actions\DepartmentAdmin\Lecturer\UpdateLecturerAction;
use App\Http\Requests\DepartmentAdmin\Lecturer\StoreLecturerRequest;
use App\Http\Requests\DepartmentAdmin\Lecturer\UpdateLecturerRequest;
use App\Models\Lecturer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LecturerController extends DepartmentAdminController
{
    public function index(Request $request, ListLecturersAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Lecturers retrieved successfully',
            'data'    => $action->execute($request, $this->departmentId($request)),
        ]);
    }

    public function store(StoreLecturerRequest $request, CreateLecturerAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Lecturer created successfully',
            'data'    => $action->execute($request->validated(), $this->departmentId($request))->load('user:id,name,email,is_active'),
        ], 201);
    }

    public function show(Request $request, Lecturer $lecturer, ShowLecturerAction $action): JsonResponse
    {
        $this->ensureSameDepartment($request, $lecturer->department_id);

        return response()->json([
            'message' => 'Lecturer retrieved successfully',
            'data'    => $action->execute($lecturer),
        ]);
    }

    public function update(UpdateLecturerRequest $request, Lecturer $lecturer, UpdateLecturerAction $action): JsonResponse
    {
        $this->ensureSameDepartment($request, $lecturer->department_id);

        return response()->json([
            'message' => 'Lecturer updated successfully',
            'data'    => $action->execute($lecturer, $request->validated()),
        ]);
    }

    public function destroy(Request $request, Lecturer $lecturer, DeleteLecturerAction $action): JsonResponse
    {
        $this->ensureSameDepartment($request, $lecturer->department_id);

        $action->execute($lecturer);

        return response()->json(['message' => 'Lecturer deleted successfully']);
    }
}
