<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Actions\SuperAdmin\Faculty\CreateFacultyAction;
use App\Actions\SuperAdmin\Faculty\DeleteFacultyAction;
use App\Actions\SuperAdmin\Faculty\ListFacultiesAction;
use App\Actions\SuperAdmin\Faculty\ShowFacultyAction;
use App\Actions\SuperAdmin\Faculty\UpdateFacultyAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\Faculty\StoreFacultyRequest;
use App\Http\Requests\SuperAdmin\Faculty\UpdateFacultyRequest;
use App\Models\Faculty;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FacultyController extends Controller
{

    // List all faculties with optional search and filter
    public function index(Request $request, ListFacultiesAction $action): JsonResponse
    {
        $faculties = $action->execute($request);

        return response()->json([
            'message' => 'Faculties retrieved successfully',
            'data'    => $faculties,
        ]);
    }

    // Store a new faculty
    public function store(StoreFacultyRequest $request, CreateFacultyAction $action): JsonResponse
    {
        $faculty = $action->execute($request->validated());

        return response()->json([
            'message' => 'Faculty created successfully',
            'data'    => $faculty,
        ], 201);
    }

    // Show a specific faculty along with its associated departments
    public function show(Faculty $faculty, ShowFacultyAction $action): JsonResponse
    {
        $faculty = $action->execute($faculty);

        return response()->json([
            'message' => 'Faculty retrieved successfully',
            'data'    => $faculty,
        ]);
    }

    // Update a faculty's details
    public function update(UpdateFacultyRequest $request, Faculty $faculty, UpdateFacultyAction $action): JsonResponse
    {
        $faculty = $action->execute($faculty, $request->validated());

        return response()->json([
            'message' => 'Faculty updated successfully',
            'data'    => $faculty,
        ]);
    }

    // Delete a faculty only if it has no associated departments
    public function destroy(Faculty $faculty, DeleteFacultyAction $action): JsonResponse
    {
        $action->execute($faculty);

        return response()->json([
            'message' => 'Faculty deleted successfully',
        ]);
    }
}