<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Actions\SuperAdmin\Course\CreateCourseAction;
use App\Actions\SuperAdmin\Course\DeleteCourseAction;
use App\Actions\SuperAdmin\Course\ListCoursesAction;
use App\Actions\SuperAdmin\Course\ShowCourseAction;
use App\Actions\SuperAdmin\Course\UpdateCourseAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\Course\StoreCourseRequest;
use App\Http\Requests\SuperAdmin\Course\UpdateCourseRequest;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request, ListCoursesAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Courses retrieved successfully',
            'data'    => $action->execute($request),
        ]);
    }

    public function store(StoreCourseRequest $request, CreateCourseAction $action): JsonResponse
    {
        $course = $action->execute($request->validated());

        return response()->json([
            'message' => 'Course created successfully',
            'data'    => $course->load('department:id,name,code'),
        ], 201);
    }

    public function show(Course $course, ShowCourseAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Course retrieved successfully',
            'data'    => $action->execute($course),
        ]);
    }

    public function update(UpdateCourseRequest $request, Course $course, UpdateCourseAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Course updated successfully',
            'data'    => $action->execute($course, $request->validated()),
        ]);
    }

    public function destroy(Course $course, DeleteCourseAction $action): JsonResponse
    {
        $action->execute($course);

        return response()->json(['message' => 'Course deleted successfully']);
    }
}
