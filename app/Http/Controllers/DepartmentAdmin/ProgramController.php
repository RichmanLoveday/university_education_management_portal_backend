<?php

namespace App\Http\Controllers\DepartmentAdmin;

use App\Actions\DepartmentAdmin\Program\CreateProgramAction;
use App\Actions\DepartmentAdmin\Program\DeleteProgramAction;
use App\Actions\DepartmentAdmin\Program\ListProgramsAction;
use App\Actions\DepartmentAdmin\Program\UpdateProgramAction;
use App\Http\Requests\DepartmentAdmin\Program\StoreProgramRequest;
use App\Http\Requests\DepartmentAdmin\Program\UpdateProgramRequest;
use App\Models\Program;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgramController extends DepartmentAdminController
{
    public function index(Request $request, ListProgramsAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Programs retrieved successfully',
            'data'    => $action->execute($request, $this->departmentId($request)),
        ]);
    }

    public function store(StoreProgramRequest $request, CreateProgramAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Program created successfully',
            'data'    => $action->execute($request->validated(), $this->departmentId($request)),
        ], 201);
    }

    public function show(Request $request, Program $program): JsonResponse
    {
        $this->ensureSameDepartment($request, $program->department_id);

        return response()->json([
            'message' => 'Program retrieved successfully',
            'data'    => $program->loadCount('students'),
        ]);
    }

    public function update(UpdateProgramRequest $request, Program $program, UpdateProgramAction $action): JsonResponse
    {
        $this->ensureSameDepartment($request, $program->department_id);

        return response()->json([
            'message' => 'Program updated successfully',
            'data'    => $action->execute($program, $request->validated()),
        ]);
    }

    public function destroy(Request $request, Program $program, DeleteProgramAction $action): JsonResponse
    {
        $this->ensureSameDepartment($request, $program->department_id);

        $action->execute($program);

        return response()->json(['message' => 'Program deleted successfully']);
    }
}
