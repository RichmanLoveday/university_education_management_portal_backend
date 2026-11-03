<?php

namespace App\Actions\SuperAdmin\CourseAllocation;

use App\Models\CourseAllocation;

class ShowCourseAllocationAction
{
    public function execute(CourseAllocation $courseAllocation): CourseAllocation
    {
        return $courseAllocation->load([
            'course.department:id,name,code',
            'lecturer.user:id,name,email',
            'academicSession:id,name',
            'semester:id,name',
        ]);
    }
}
