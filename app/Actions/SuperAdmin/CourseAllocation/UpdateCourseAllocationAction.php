<?php

namespace App\Actions\SuperAdmin\CourseAllocation;

use App\Models\CourseAllocation;

class UpdateCourseAllocationAction
{
    public function execute(CourseAllocation $courseAllocation, array $data): CourseAllocation
    {
        $courseAllocation->update($data);

        return $courseAllocation->fresh([
            'course.department:id,name,code',
            'lecturer.user:id,name,email',
            'academicSession:id,name',
            'semester:id,name',
        ]);
    }
}
