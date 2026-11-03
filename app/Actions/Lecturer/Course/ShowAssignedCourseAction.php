<?php

namespace App\Actions\Lecturer\Course;

use App\Models\CourseAllocation;

class ShowAssignedCourseAction
{
    public function execute(CourseAllocation $allocation): array
    {
        $students = $allocation->registrationsQuery()
            ->with([
                'student:id,user_id,matric_number,academic_level,program_id',
                'student.user:id,name,email',
                'student.program:id,name',
            ])
            ->get()
            ->sortBy('student.matric_number')
            ->values()
            ->map(fn($registration) => [
                'course_registration_id' => $registration->id,
                'student_id'             => $registration->student_id,
                'matric_number'          => $registration->student->matric_number,
                'name'                   => $registration->student->user?->name,
                'email'                  => $registration->student->user?->email,
                'academic_level'         => $registration->student->academic_level,
                'program'                => $registration->student->program?->name,
                'registration_date'      => $registration->registration_date,
            ]);

        return [
            'allocation' => $allocation->load([
                'course:id,department_id,code,title,units,level,semester,description',
                'course.department:id,name,code',
                'academicSession:id,name',
                'semester:id,name,number',
            ]),
            'total_students' => $students->count(),
            'class_list'     => $students,
        ];
    }
}
