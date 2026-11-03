<?php

namespace App\Actions\SuperAdmin\Result;

use App\Models\Result;
use Illuminate\Http\Request;

class ListResultsAction
{
    public function execute(Request $request)
    {
        return Result::query()
            ->with([
                'courseRegistration:id,student_id,course_id,academic_session_id,semester_id',
                'courseRegistration.student:id,user_id,matric_number,department_id',
                'courseRegistration.student.user:id,name',
                'courseRegistration.course:id,code,title,units,department_id',
                'courseRegistration.academicSession:id,name',
                'courseRegistration.semester:id,name',
                'lecturer.user:id,name',
                'approvedBy:id,name',
            ])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->lecturer_id, fn($q) => $q->where('lecturer_id', $request->lecturer_id))
            ->whereHas('courseRegistration', fn($q) => $q
                ->when($request->academic_session_id, fn($q) => $q->where('academic_session_id', $request->academic_session_id))
                ->when($request->semester_id, fn($q) => $q->where('semester_id', $request->semester_id))
                ->when($request->course_id, fn($q) => $q->where('course_id', $request->course_id))
                ->when($request->student_id, fn($q) => $q->where('student_id', $request->student_id))
                ->when($request->department_id, fn($q) => $q->whereHas('course', fn($q2) => $q2->where('department_id', $request->department_id))))
            ->latest('updated_at')
            ->paginate($request->per_page ?? 15);
    }
}
