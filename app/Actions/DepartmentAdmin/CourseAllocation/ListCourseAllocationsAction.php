<?php

namespace App\Actions\DepartmentAdmin\CourseAllocation;

use App\Models\CourseAllocation;
use Illuminate\Http\Request;

class ListCourseAllocationsAction
{
    public function execute(Request $request, int $departmentId)
    {
        return CourseAllocation::query()
            ->whereHas('course', fn($q) => $q->where('department_id', $departmentId))
            ->with([
                'course:id,code,title,units,level,semester,department_id',
                'lecturer:id,staff_id,user_id',
                'lecturer.user:id,name,email',
                'academicSession:id,name',
                'semester:id,name,number',
            ])
            ->when($request->academic_session_id, fn($q) => $q->where('academic_session_id', $request->academic_session_id))
            ->when($request->semester_id, fn($q) => $q->where('semester_id', $request->semester_id))
            ->when($request->lecturer_id, fn($q) => $q->where('lecturer_id', $request->lecturer_id))
            ->when($request->search, function ($q, $search) {
                $q->whereHas('course', function ($q2) use ($search) {
                    $q2->where(fn($q3) => $q3->where('code', 'like', "%{$search}%")->orWhere('title', 'like', "%{$search}%"));
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 15);
    }
}
