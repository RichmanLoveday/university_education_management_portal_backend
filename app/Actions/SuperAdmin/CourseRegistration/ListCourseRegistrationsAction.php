<?php

namespace App\Actions\SuperAdmin\CourseRegistration;

use App\Models\CourseRegistration;
use Illuminate\Http\Request;

class ListCourseRegistrationsAction
{
    public function execute(Request $request)
    {
        return CourseRegistration::query()
            ->with([
                'student:id,user_id,matric_number,academic_level,department_id',
                'student.user:id,name',
                'student.department:id,name,code',
                'course:id,code,title,units,level',
                'academicSession:id,name',
                'semester:id,name',
            ])
            ->when($request->academic_session_id, fn($q) => $q->where('academic_session_id', $request->academic_session_id))
            ->when($request->semester_id, fn($q) => $q->where('semester_id', $request->semester_id))
            ->when($request->course_id, fn($q) => $q->where('course_id', $request->course_id))
            ->when($request->student_id, fn($q) => $q->where('student_id', $request->student_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->department_id, fn($q) => $q->whereHas('student', fn($q2) => $q2->where('department_id', $request->department_id)))
            ->latest()
            ->paginate($request->per_page ?? 15);
    }
}
