<?php

namespace App\Actions\DepartmentAdmin\CourseRegistration;

use App\Models\CourseRegistration;
use Illuminate\Http\Request;

class ListCourseRegistrationsAction
{
    public function execute(Request $request, int $departmentId)
    {
        return CourseRegistration::query()
            ->whereHas('student', fn($q) => $q->where('department_id', $departmentId))
            ->with([
                'student:id,user_id,matric_number,academic_level',
                'student.user:id,name,email',
                'course:id,code,title,units,level',
                'academicSession:id,name',
                'semester:id,name',
            ])
            ->when($request->academic_session_id, fn($q) => $q->where('academic_session_id', $request->academic_session_id))
            ->when($request->semester_id, fn($q) => $q->where('semester_id', $request->semester_id))
            ->when($request->course_id, fn($q) => $q->where('course_id', $request->course_id))
            ->when($request->student_id, fn($q) => $q->where('student_id', $request->student_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->academic_level, fn($q) => $q->whereHas('student', fn($q2) => $q2->where('academic_level', $request->academic_level)))
            ->when($request->search, function ($q, $search) {
                $q->whereHas('student', fn($q2) => $q2->where(fn($q3) => $q3
                    ->where('matric_number', 'like', "%{$search}%")
                    ->orWhereHas('user', fn($q4) => $q4->where('name', 'like', "%{$search}%"))));
            })
            ->latest()
            ->paginate($request->per_page ?? 15);
    }
}
