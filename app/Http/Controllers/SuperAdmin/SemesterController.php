<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Actions\SuperAdmin\Semester\CreateSemesterAction;
use App\Actions\SuperAdmin\Semester\DeleteSemesterAction;
use App\Actions\SuperAdmin\Semester\ListSemestersAction;
use App\Actions\SuperAdmin\Semester\SetCurrentSemesterAction;
use App\Actions\SuperAdmin\Semester\ShowSemesterAction;
use App\Actions\SuperAdmin\Semester\UpdateSemesterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\Semester\StoreSemesterRequest;
use App\Http\Requests\SuperAdmin\Semester\UpdateSemesterRequest;
use App\Models\Semester;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
    /*
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Actions\SuperAdmin\Semester\ListSemestersAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ListSemestersAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Semesters retrieved successfully',
            'data'    => $action->execute($request),
        ]);
    }

    /*
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\SuperAdmin\Semester\StoreSemesterRequest  $request
     * @param  \App\Actions\SuperAdmin\Semester\CreateSemesterAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreSemesterRequest $request, CreateSemesterAction $action): JsonResponse
    {
        $semester = $action->execute($request->validated());

        return response()->json([
            'message' => 'Semester created successfully',
            'data'    => $semester->load('academicSession:id,name'),
        ], 201);
    }

    /*
     * Display the specified resource.
     *
     * @param  \App\Models\Semester  $semester
     * @param  \App\Actions\SuperAdmin\Semester\ShowSemesterAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Semester $semester, ShowSemesterAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Semester retrieved successfully',
            'data'    => $action->execute($semester),
        ]);
    }

    /*
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\SuperAdmin\Semester\UpdateSemesterRequest  $request
     * @param  \App\Models\Semester  $semester
     * @param  \App\Actions\SuperAdmin\Semester\UpdateSemesterAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateSemesterRequest $request, Semester $semester, UpdateSemesterAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Semester updated successfully',
            'data'    => $action->execute($semester, $request->validated()),
        ]);
    }

    /*
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Semester  $semester
     * @param  \App\Actions\SuperAdmin\Semester\DeleteSemesterAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Semester $semester, DeleteSemesterAction $action): JsonResponse
    {
        $action->execute($semester);

        return response()->json([
            'message' => 'Semester deleted successfully',
        ]);
    }

    /*
     * Set the specified semester as the current semester.
     *
     * @param  \App\Models\Semester  $semester
     * @param  \App\Actions\SuperAdmin\Semester\SetCurrentSemesterAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function setCurrent(Semester $semester, SetCurrentSemesterAction $action): JsonResponse
    {
        $semester = $action->execute($semester);

        return response()->json([
            'message' => 'Semester set as current successfully',
            'data'    => $semester,
        ]);
    }
}