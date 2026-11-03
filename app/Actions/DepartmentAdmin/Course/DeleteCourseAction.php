<?php

namespace App\Actions\DepartmentAdmin\Course;

use App\Models\Course;
use Illuminate\Validation\ValidationException;

class DeleteCourseAction
{
    public function execute(Course $course): void
    {
        if ($course->courseRegistrations()->exists() || $course->courseAllocations()->exists()) {
            throw ValidationException::withMessages([
                'course' => ['Cannot delete course. It has registrations or allocations.'],
            ]);
        }

        $course->delete();
    }
}
