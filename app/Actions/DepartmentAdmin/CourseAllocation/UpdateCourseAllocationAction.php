<?php

namespace App\Actions\DepartmentAdmin\CourseAllocation;

use App\Models\CourseAllocation;
use App\Models\Result;
use Illuminate\Support\Facades\DB;

class UpdateCourseAllocationAction
{
    public function execute(CourseAllocation $courseAllocation, array $data): CourseAllocation
    {
        return DB::transaction(function () use ($courseAllocation, $data) {
            $courseAllocation->update($data);

            // Hand over unsubmitted results to the newly allocated lecturer
            if (isset($data['lecturer_id'])) {
                Result::query()
                    ->whereIn('status', ['draft', 'rejected'])
                    ->whereHas('courseRegistration', fn($q) => $q
                        ->where('course_id', $courseAllocation->course_id)
                        ->where('academic_session_id', $courseAllocation->academic_session_id)
                        ->where('semester_id', $courseAllocation->semester_id))
                    ->update(['lecturer_id' => $data['lecturer_id']]);
            }

            return $courseAllocation->fresh([
                'course:id,code,title,units,level',
                'lecturer.user:id,name,email',
                'academicSession:id,name',
                'semester:id,name',
            ]);
        });
    }
}
