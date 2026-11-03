<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Actions\SuperAdmin\CourseAllocation\CreateCourseAllocationAction;
use App\Actions\SuperAdmin\CourseAllocation\DeleteCourseAllocationAction;
use App\Actions\SuperAdmin\CourseAllocation\ListCourseAllocationsAction;
use App\Actions\SuperAdmin\CourseAllocation\ShowCourseAllocationAction;
use App\Actions\SuperAdmin\CourseAllocation\UpdateCourseAllocationAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\CourseAllocation\StoreCourseAllocationRequest;
use App\Http\Requests\SuperAdmin\CourseAllocation\UpdateCourseAllocationRequest;
use App\Models\CourseAllocation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseAllocationController extends Controller
{
    public function index(Request $request, ListCourseAllocationsAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Course allocations retrieved successfully',
            'data'    => $action->execute($request),
        ]);
    }

    public function store(StoreCourseAllocationRequest $request, CreateCourseAllocationAction $action): JsonResponse
    {
        $allocation = $action->execute($request->validated());

        return response()->json([
            'message' => 'Course allocated successfully',
            'data'    => $allocation->load([
                'course.department:id,name,code',
                'lecturer.user:id,name,email',
                'academicSession:id,name',
                'semester:id,name',
            ]),
        ], 201);
    }

    public function show(CourseAllocation $course_allocation, ShowCourseAllocationAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Course allocation retrieved successfully',
            'data'    => $action->execute($course_allocation),
        ]);
    }

    public function update(UpdateCourseAllocationRequest $request, CourseAllocation $course_allocation, UpdateCourseAllocationAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Course allocation updated successfully',
            'data'    => $action->execute($course_allocation, $request->validated()),
        ]);
    }

    public function destroy(CourseAllocation $course_allocation, DeleteCourseAllocationAction $action): JsonResponse
    {
        $action->execute($course_allocation);

        return response()->json([
            'message' => 'Course allocation deleted successfully',
        ]);
    }
}