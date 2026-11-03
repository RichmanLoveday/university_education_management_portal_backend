<?php

namespace App\Http\Controllers\DepartmentAdmin;

use App\Actions\DepartmentAdmin\Student\CreateStudentAction;
use App\Actions\DepartmentAdmin\Student\DeleteStudentAction;
use App\Actions\DepartmentAdmin\Student\ListStudentsAction;
use App\Actions\DepartmentAdmin\Student\ShowStudentAction;
use App\Actions\DepartmentAdmin\Student\UpdateStudentAction;
use App\Http\Requests\DepartmentAdmin\Student\StoreStudentRequest;
use App\Http\Requests\DepartmentAdmin\Student\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends DepartmentAdminController
{
    public function index(Request $request, ListStudentsAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Students retrieved successfully',
            'data'    => $action->execute($request, $this->departmentId($request)),
        ]);
    }

    public function store(StoreStudentRequest $request, CreateStudentAction $action): JsonResponse
    {
        $student = $action->execute($request->validated(), $this->departmentId($request));

        return response()->json([
            'message' => 'Student created successfully',
            'data'    => $student->load(['user', 'program']),
        ], 201);
    }

    public function show(Request $request, Student $student, ShowStudentAction $action): JsonResponse
    {
        $this->ensureSameDepartment($request, $student->department_id);

        return response()->json([
            'message' => 'Student retrieved successfully',
            'data'    => $action->execute($student),
        ]);
    }

    public function update(UpdateStudentRequest $request, Student $student, UpdateStudentAction $action): JsonResponse
    {
        $this->ensureSameDepartment($request, $student->department_id);

        return response()->json([
            'message' => 'Student updated successfully',
            'data'    => $action->execute($student, $request->validated()),
        ]);
    }

    public function destroy(Request $request, Student $student, DeleteStudentAction $action): JsonResponse
    {
        $this->ensureSameDepartment($request, $student->department_id);

        $action->execute($student);

        return response()->json(['message' => 'Student deleted successfully']);
    }
}
