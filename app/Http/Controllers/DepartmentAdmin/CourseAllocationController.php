<?php

namespace App\Http\Controllers\DepartmentAdmin;

use App\Actions\DepartmentAdmin\CourseAllocation\CreateCourseAllocationAction;
use App\Actions\DepartmentAdmin\CourseAllocation\DeleteCourseAllocationAction;
use App\Actions\DepartmentAdmin\CourseAllocation\ListCourseAllocationsAction;
use App\Actions\DepartmentAdmin\CourseAllocation\UpdateCourseAllocationAction;
use App\Http\Requests\DepartmentAdmin\CourseAllocation\StoreCourseAllocationRequest;
use App\Http\Requests\DepartmentAdmin\CourseAllocation\UpdateCourseAllocationRequest;
use App\Models\CourseAllocation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseAllocationController extends DepartmentAdminController
{
    public function index(Request $request, ListCourseAllocationsAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Course allocations retrieved successfully',
            'data'    => $action->execute($request, $this->departmentId($request)),
        ]);
    }

    public function store(StoreCourseAllocationRequest $request, CreateCourseAllocationAction $action): JsonResponse
    {
        $allocation = $action->execute($request->validated());

        return response()->json([
            'message' => 'Course allocated successfully',
            'data'    => $allocation->load([
                'course:id,code,title,units,level',
                'lecturer.user:id,name,email',
                'academicSession:id,name',
                'semester:id,name',
            ]),
        ], 201);
    }

    public function show(Request $request, CourseAllocation $course_allocation): JsonResponse
    {
        $this->ensureSameDepartment($request, $course_allocation->course->department_id);

        return response()->json([
            'message' => 'Course allocation retrieved successfully',
            'data'    => $course_allocation->load([
                'course:id,code,title,units,level,semester',
                'lecturer.user:id,name,email',
                'academicSession:id,name',
                'semester:id,name',
            ])->setAttribute('registered_students', $course_allocation->registrationsQuery()->count()),
        ]);
    }

    public function update(UpdateCourseAllocationRequest $request, CourseAllocation $course_allocation, UpdateCourseAllocationAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Course allocation updated successfully',
            'data'    => $action->execute($course_allocation, $request->validated()),
        ]);
    }

    public function destroy(Request $request, CourseAllocation $course_allocation, DeleteCourseAllocationAction $action): JsonResponse
    {
        $this->ensureSameDepartment($request, $course_allocation->course->department_id);

        $action->execute($course_allocation);

        return response()->json(['message' => 'Course allocation deleted successfully']);
    }
}
