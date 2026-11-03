<?php

namespace App\Actions\SuperAdmin\Course;

use App\Models\Course;

class UpdateCourseAction
{
    public function execute(Course $course, array $data): Course
    {
        $course->update($data);
        return $course->fresh(['department:id,name,code']);
    }
}
