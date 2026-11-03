<?php

namespace App\Actions\DepartmentAdmin\Course;

use App\Models\Course;

class CreateCourseAction
{
    public function execute(array $data, int $departmentId): Course
    {
        return Course::create([...$data, 'department_id' => $departmentId]);
    }
}
