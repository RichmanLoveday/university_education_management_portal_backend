<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Actions\SuperAdmin\Student\CreateStudentAction;
use App\Actions\SuperAdmin\Student\DeleteStudentAction;
use App\Actions\SuperAdmin\Student\ListStudentsAction;
use App\Actions\SuperAdmin\Student\ShowStudentAction;
use App\Actions\SuperAdmin\Student\UpdateStudentAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\Student\StoreStudentRequest;
use App\Http\Requests\SuperAdmin\Student\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request, ListStudentsAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Students retrieved successfully',
            'data'    => $action->execute($request),
        ]);
    }

    public function store(StoreStudentRequest $request, CreateStudentAction $action): JsonResponse
    {
        $student = $action->execute($request->validated());

        return response()->json([
            'message' => 'Student created successfully',
            'data'    => $student->load(['user', 'department', 'program']),
        ], 201);
    }

    public function show(Student $student, ShowStudentAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Student retrieved successfully',
            'data'    => $action->execute($student),
        ]);
    }

    public function update(UpdateStudentRequest $request, Student $student, UpdateStudentAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Student updated successfully',
            'data'    => $action->execute($student, $request->validated()),
        ]);
    }

    public function destroy(Student $student, DeleteStudentAction $action): JsonResponse
    {
        $action->execute($student);

        return response()->json(['message' => 'Student deleted successfully']);
    }
}
