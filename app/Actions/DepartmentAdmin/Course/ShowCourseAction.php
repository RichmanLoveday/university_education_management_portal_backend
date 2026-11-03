<?php

namespace App\Actions\DepartmentAdmin\Course;

use App\Models\Course;

class ShowCourseAction
{
    public function execute(Course $course): Course
    {
        return $course->load([
            'department:id,name,code',
            'courseAllocations.lecturer.user:id,name,email',
            'courseAllocations.academicSession:id,name',
            'courseAllocations.semester:id,name',
        ])->loadCount(['courseRegistrations' => fn($q) => $q->where('status', 'registered')]);
    }
}
