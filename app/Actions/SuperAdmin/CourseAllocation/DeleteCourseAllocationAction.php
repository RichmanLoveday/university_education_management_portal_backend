<?php

namespace App\Actions\SuperAdmin\CourseAllocation;

use App\Models\CourseAllocation;
use Illuminate\Validation\ValidationException;

class DeleteCourseAllocationAction
{
    public function execute(CourseAllocation $courseAllocation): void
    {
        // Prevent deletion if results have already been entered for this allocation
        $hasResults = $courseAllocation->course
            ->courseRegistrations()
            ->where('academic_session_id', $courseAllocation->academic_session_id)
            ->where('semester_id', $courseAllocation->semester_id)
            ->whereHas('result')
            ->exists();

        if ($hasResults) {
            throw ValidationException::withMessages([
                'allocation' => ['Cannot delete allocation. Results have already been entered for this course in the selected session/semester.'],
            ]);
        }

        $courseAllocation->delete();
    }
}
