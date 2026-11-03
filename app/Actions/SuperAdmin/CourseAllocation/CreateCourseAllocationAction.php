<?php

namespace App\Actions\SuperAdmin\CourseAllocation;

use App\Models\CourseAllocation;

class CreateCourseAllocationAction
{
    public function execute(array $data): CourseAllocation
    {
        return CourseAllocation::create($data);
    }
}
