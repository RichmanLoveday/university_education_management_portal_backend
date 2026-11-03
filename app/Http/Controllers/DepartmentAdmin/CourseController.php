<?php

namespace App\Http\Controllers\DepartmentAdmin;

use App\Actions\DepartmentAdmin\Course\CreateCourseAction;
use App\Actions\DepartmentAdmin\Course\DeleteCourseAction;
use App\Actions\DepartmentAdmin\Course\ShowCourseAction;
use App\Actions\DepartmentAdmin\Course\ListCoursesAction;
use App\Actions\DepartmentAdmin\Course\UpdateCourseAction;
use App\Http\Requests\DepartmentAdmin\Course\StoreCourseRequest;
use App\Http\Requests\DepartmentAdmin\Course\UpdateCourseRequest;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends DepartmentAdminController
{
    public function index(Request $request, ListCoursesAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Courses retrieved successfully',
            'data'    => $action->execute($request, $this->departmentId($request)),
        ]);
    }

    public function store(StoreCourseRequest $request, CreateCourseAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Course created successfully',
            'data'    => $action->execute($request->validated(), $this->departmentId($request)),
        ], 201);
    }

    public function show(Request $request, Course $course, ShowCourseAction $action): JsonResponse
    {
        $this->ensureSameDepartment($request, $course->department_id);

        return response()->json([
            'message' => 'Course retrieved successfully',
            'data'    => $action->execute($course),
        ]);
    }

    public function update(UpdateCourseRequest $request, Course $course, UpdateCourseAction $action): JsonResponse
    {
        $this->ensureSameDepartment($request, $course->department_id);

        return response()->json([
            'message' => 'Course updated successfully',
            'data'    => $action->execute($course, $request->validated()),
        ]);
    }

    public function destroy(Request $request, Course $course, DeleteCourseAction $action): JsonResponse
    {
        $this->ensureSameDepartment($request, $course->department_id);

        $action->execute($course);

        return response()->json(['message' => 'Course deleted successfully']);
    }
}
