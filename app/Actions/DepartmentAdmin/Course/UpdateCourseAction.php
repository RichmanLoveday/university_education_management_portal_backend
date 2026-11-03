<?php

namespace App\Actions\DepartmentAdmin\Course;

use App\Models\Course;

class UpdateCourseAction
{
    public function execute(Course $course, array $data): Course
    {
        $course->update(collect($data)->except('department_id')->toArray());

        return $course->fresh();
    }
}
