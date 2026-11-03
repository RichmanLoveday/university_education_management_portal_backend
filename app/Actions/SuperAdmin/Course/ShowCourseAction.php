<?php

namespace App\Actions\SuperAdmin\Course;

use App\Models\Course;

class ShowCourseAction
{
    public function execute(Course $course): Course
    {
        return $course->load('department:id,name,code');
    }
}
