<?php

namespace App\Http\Controllers\DepartmentAdmin;

use App\Actions\DepartmentAdmin\CourseRegistration\ListCourseRegistrationsAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseRegistrationController extends DepartmentAdminController
{
    public function index(Request $request, ListCourseRegistrationsAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Course registrations retrieved successfully',
            'data'    => $action->execute($request, $this->departmentId($request)),
        ]);
    }
}
