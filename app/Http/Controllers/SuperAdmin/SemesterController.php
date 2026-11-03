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
    public function index(Request $request, ListSemestersAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Semesters retrieved successfully',
            'data'    => $action->execute($request),
        ]);
    }

    public function store(StoreSemesterRequest $request, CreateSemesterAction $action): JsonResponse
    {
        $semester = $action->execute($request->validated());

        return response()->json([
            'message' => 'Semester created successfully',
            'data'    => $semester->load('academicSession:id,name'),
        ], 201);
    }

    public function show(Semester $semester, ShowSemesterAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Semester retrieved successfully',
            'data'    => $action->execute($semester),
        ]);
    }

    public function update(UpdateSemesterRequest $request, Semester $semester, UpdateSemesterAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Semester updated successfully',
            'data'    => $action->execute($semester, $request->validated()),
        ]);
    }

    public function destroy(Semester $semester, DeleteSemesterAction $action): JsonResponse
    {
        $action->execute($semester);

        return response()->json([
            'message' => 'Semester deleted successfully',
        ]);
    }

    public function setCurrent(Semester $semester, SetCurrentSemesterAction $action): JsonResponse
    {
        $semester = $action->execute($semester);

        return response()->json([
            'message' => 'Semester set as current successfully',
            'data'    => $semester,
        ]);
    }
}