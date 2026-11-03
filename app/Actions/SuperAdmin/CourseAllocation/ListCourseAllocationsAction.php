<?php

namespace App\Actions\SuperAdmin\CourseAllocation;

use App\Models\CourseAllocation;
use Illuminate\Http\Request;

class ListCourseAllocationsAction
{
    public function execute(Request $request)
    {
        // list course allocations with optional filters for academic session, semester, department, lecturer, and search term
        return CourseAllocation::query()
            ->with([
                'course:id,code,title,units,level,department_id',
                'course.department:id,name,code',
                'lecturer:id,staff_id,user_id',
                'lecturer.user:id,name,email',
                'academicSession:id,name',
                'semester:id,name',
            ])
            ->when($request->academic_session_id, fn($q) => $q->where('academic_session_id', $request->academic_session_id))
            ->when($request->semester_id, fn($q) => $q->where('semester_id', $request->semester_id))
            ->when($request->department_id, function ($q) use ($request) {
                $q->whereHas('course', fn($q2) => $q2->where('department_id', $request->department_id));
            })
            ->when($request->lecturer_id, fn($q) => $q->where('lecturer_id', $request->lecturer_id))
            ->when($request->search, function ($q, $search) {
                $q->whereHas('course', function ($q2) use ($search) {
                    $q2->where('code', 'like', "%{$search}%")
                       ->orWhere('title', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($request->per_page ?? 15);
    }
}