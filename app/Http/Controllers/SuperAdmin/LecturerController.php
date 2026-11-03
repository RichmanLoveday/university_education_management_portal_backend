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
    public function index(Request $request, ListLecturersAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Lecturers retrieved successfully',
            'data'    => $action->execute($request),
        ]);
    }

    public function store(StoreLecturerRequest $request, CreateLecturerAction $action): JsonResponse
    {
        $lecturer = $action->execute($request->validated());

        return response()->json([
            'message' => 'Lecturer created successfully',
            'data'    => $lecturer->load(['user', 'department']),
        ], 201);
    }

    public function show(Lecturer $lecturer, ShowLecturerAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Lecturer retrieved successfully',
            'data'    => $action->execute($lecturer),
        ]);
    }

    public function update(UpdateLecturerRequest $request, Lecturer $lecturer, UpdateLecturerAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Lecturer updated successfully',
            'data'    => $action->execute($lecturer, $request->validated()),
        ]);
    }

    public function destroy(Lecturer $lecturer, DeleteLecturerAction $action): JsonResponse
    {
        $action->execute($lecturer);

        return response()->json(['message' => 'Lecturer deleted successfully']);
    }
}