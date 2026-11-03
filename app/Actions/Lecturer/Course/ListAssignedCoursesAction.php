<?php

namespace App\Actions\Lecturer\Course;

use App\Models\CourseAllocation;
use App\Models\Lecturer;
use Illuminate\Http\Request;

class ListAssignedCoursesAction
{
    public function execute(Request $request, Lecturer $lecturer)
    {
        $allocations = CourseAllocation::query()
            ->where('lecturer_id', $lecturer->id)
            ->with([
                'course:id,department_id,code,title,units,level,semester',
                'course.department:id,name,code',
                'academicSession:id,name,is_current',
                'semester:id,name,number,is_current',
            ])
            ->when($request->academic_session_id, fn($q) => $q->where('academic_session_id', $request->academic_session_id))
            ->when($request->semester_id, fn($q) => $q->where('semester_id', $request->semester_id))
            ->when($request->boolean('current'), fn($q) => $q
                ->whereHas('academicSession', fn($q) => $q->where('is_current', true))
                ->whereHas('semester', fn($q) => $q->where('is_current', true)))
            ->latest()
            ->paginate($request->per_page ?? 15);

        // Attach per-course counts so the lecturer sees progress at a glance
        $allocations->getCollection()->transform(function (CourseAllocation $allocation) {
            $allocation->setAttribute('registered_students', $allocation->registrationsQuery()->count());
            $allocation->setAttribute('result_counts', $allocation->resultsQuery()
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'));

            return $allocation;
        });

        return $allocations;
    }
}
