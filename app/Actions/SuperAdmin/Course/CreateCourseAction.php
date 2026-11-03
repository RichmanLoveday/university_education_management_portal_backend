<?php

namespace App\Actions\SuperAdmin\Course;

use App\Models\Course;

class CreateCourseAction
{
    public function execute(array $data): Course
    {
        return Course::create($data);
    }
}
