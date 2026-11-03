<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Actions\SuperAdmin\Program\CreateProgramAction;
use App\Actions\SuperAdmin\Program\DeleteProgramAction;
use App\Actions\SuperAdmin\Program\ListProgramsAction;
use App\Actions\SuperAdmin\Program\ShowProgramAction;
use App\Actions\SuperAdmin\Program\UpdateProgramAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\Program\StoreProgramRequest;
use App\Http\Requests\SuperAdmin\Program\UpdateProgramRequest;
use App\Models\Program;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request, ListProgramsAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Programs retrieved successfully',
            'data'    => $action->execute($request),
        ]);
    }

    public function store(StoreProgramRequest $request, CreateProgramAction $action): JsonResponse
    {
        $program = $action->execute($request->validated());

        return response()->json([
            'message' => 'Program created successfully',
            'data'    => $program->load('department:id,name,code'),
        ], 201);
    }

    public function show(Program $program, ShowProgramAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Program retrieved successfully',
            'data'    => $action->execute($program),
        ]);
    }

    public function update(UpdateProgramRequest $request, Program $program, UpdateProgramAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Program updated successfully',
            'data'    => $action->execute($program, $request->validated()),
        ]);
    }

    public function destroy(Program $program, DeleteProgramAction $action): JsonResponse
    {
        $action->execute($program);

        return response()->json(['message' => 'Program deleted successfully']);
    }
}
