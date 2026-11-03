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
    /*
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Actions\SuperAdmin\Program\ListProgramsAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ListProgramsAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Programs retrieved successfully',
            'data'    => $action->execute($request),
        ]);
    }

    /*
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SuperAdmin\Program\StoreProgramRequest  $request
     * @param  \App\Actions\SuperAdmin\Program\CreateProgramAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreProgramRequest $request, CreateProgramAction $action): JsonResponse
    {
        $program = $action->execute($request->validated());

        return response()->json([
            'message' => 'Program created successfully',
            'data'    => $program->load('department:id,name,code'),
        ], 201);
    }

    /*
     * Display the specified resource.
     *
     * @param  \App\Models\Program  $program
     * @param  \App\Actions\SuperAdmin\Program\ShowProgramAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Program $program, ShowProgramAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Program retrieved successfully',
            'data'    => $action->execute($program),
        ]);
    }

    /*
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\SuperAdmin\Program\UpdateProgramRequest  $request
     * @param  \App\Models\Program  $program
     * @param  \App\Actions\SuperAdmin\Program\UpdateProgramAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProgramRequest $request, Program $program, UpdateProgramAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Program updated successfully',
            'data'    => $action->execute($program, $request->validated()),
        ]);
    }

    /*
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Program  $program
     * @param  \App\Actions\SuperAdmin\Program\DeleteProgramAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Program $program, DeleteProgramAction $action): JsonResponse
    {
        $action->execute($program);

        return response()->json(['message' => 'Program deleted successfully']);
    }
}
