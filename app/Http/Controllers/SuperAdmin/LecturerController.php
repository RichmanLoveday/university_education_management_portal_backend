<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Actions\SuperAdmin\Lecturer\CreateLecturerAction;
use App\Actions\SuperAdmin\Lecturer\DeleteLecturerAction;
use App\Actions\SuperAdmin\Lecturer\ListLecturersAction;
use App\Actions\SuperAdmin\Lecturer\ShowLecturerAction;
use App\Actions\SuperAdmin\Lecturer\UpdateLecturerAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\Lecturer\StoreLecturerRequest;
use App\Http\Requests\SuperAdmin\Lecturer\UpdateLecturerRequest;
use App\Models\Lecturer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LecturerController extends Controller
{
    /** Retrieve the lecturer listing. */
    public function index(Request $request, ListLecturersAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Lecturers retrieved successfully',
            'data'    => $action->execute($request),
        ]);
    }

    /** Create a lecturer from the validated request data. */
    public function store(StoreLecturerRequest $request, CreateLecturerAction $action): JsonResponse
    {
        $lecturer = $action->execute($request->validated());

        return response()->json([
            'message' => 'Lecturer created successfully',
            'data'    => $lecturer->load(['user', 'department']),
        ], 201);
    }

    /** Retrieve the specified lecturer. */
    public function show(Lecturer $lecturer, ShowLecturerAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Lecturer retrieved successfully',
            'data'    => $action->execute($lecturer),
        ]);
    }

    /** Update the specified lecturer with validated request data. */
    public function update(UpdateLecturerRequest $request, Lecturer $lecturer, UpdateLecturerAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Lecturer updated successfully',
            'data'    => $action->execute($lecturer, $request->validated()),
        ]);
    }

    /** Delete the specified lecturer. */
    public function destroy(Lecturer $lecturer, DeleteLecturerAction $action): JsonResponse
    {
        $action->execute($lecturer);

        return response()->json(['message' => 'Lecturer deleted successfully']);
    }
}