<?php

namespace App\Http\Controllers\Student;

use App\Actions\Student\Course\ListAvailableCoursesAction;
use App\Actions\Student\CourseRegistration\DropCourseAction;
use App\Actions\Student\CourseRegistration\GetRegistrationSlipAction;
use App\Actions\Student\CourseRegistration\ListRegistrationsAction;
use App\Actions\Student\CourseRegistration\RegisterCoursesAction;
use App\Http\Requests\Student\CourseRegistration\RegisterCoursesRequest;
use App\Models\CourseRegistration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseRegistrationController extends BaseStudentController
{
    public function available(Request $request, ListAvailableCoursesAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Available courses retrieved successfully',
            'data'    => $action->execute($this->student($request)),
        ]);
    }

    public function index(Request $request, ListRegistrationsAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Registered courses retrieved successfully',
            'data'    => $action->execute($request, $this->student($request)),
        ]);
    }

    public function store(RegisterCoursesRequest $request, RegisterCoursesAction $action): JsonResponse
    {
        $registrations = $action->execute($this->student($request), $request->validated('course_ids'));

        return response()->json([
            'message' => "{$registrations->count()} course(s) registered successfully",
            'data'    => $registrations,
        ], 201);
    }

    public function destroy(Request $request, CourseRegistration $course_registration, DropCourseAction $action): JsonResponse
    {
        abort_if($course_registration->student_id !== $this->student($request)->id, 403, 'You can only drop your own course registrations.');

        $action->execute($course_registration);

        return response()->json(['message' => 'Course dropped successfully']);
    }

    public function slip(Request $request, GetRegistrationSlipAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Course registration slip generated successfully',
            'data'    => $action->execute($request, $this->student($request)),
        ]);
    }
}
