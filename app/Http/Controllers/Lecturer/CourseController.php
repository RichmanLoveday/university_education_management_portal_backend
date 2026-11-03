<?php

namespace App\Http\Controllers\Lecturer;

use App\Actions\Lecturer\Course\GetCourseSummaryAction;
use App\Actions\Lecturer\Course\ListAssignedCoursesAction;
use App\Actions\Lecturer\Course\ShowAssignedCourseAction;
use App\Models\CourseAllocation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseController extends BaseLecturerController
{
    public function index(Request $request, ListAssignedCoursesAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Assigned courses retrieved successfully',
            'data'    => $action->execute($request, $this->lecturer($request)),
        ]);
    }

    public function show(Request $request, CourseAllocation $course_allocation, ShowAssignedCourseAction $action): JsonResponse
    {
        $this->ensureAssigned($request, $course_allocation);

        return response()->json([
            'message' => 'Course retrieved successfully',
            'data'    => $action->execute($course_allocation),
        ]);
    }

    public function summary(Request $request, CourseAllocation $course_allocation, GetCourseSummaryAction $action): JsonResponse
    {
        $this->ensureAssigned($request, $course_allocation);

        return response()->json([
            'message' => 'Course performance summary retrieved successfully',
            'data'    => $action->execute($course_allocation),
        ]);
    }
}
