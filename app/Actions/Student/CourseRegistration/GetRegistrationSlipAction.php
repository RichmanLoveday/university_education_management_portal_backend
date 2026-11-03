<?php

namespace App\Actions\Student\CourseRegistration;

use App\Models\Student;
use Illuminate\Http\Request;

class GetRegistrationSlipAction
{
    public function __construct(
        private ListRegistrationsAction $listRegistrationsAction
    ) {}

    public function execute(Request $request, Student $student): array
    {
        $student->load(['user:id,name,email', 'department.faculty:id,name', 'program:id,name']);
        $registrations = $this->listRegistrationsAction->execute($request, $student);

        return [
            'document'     => 'Course Registration Slip',
            'generated_at' => now()->toDateTimeString(),
            'student'      => [
                'name'           => $student->user->name,
                'matric_number'  => $student->matric_number,
                'faculty'        => $student->department?->faculty?->name,
                'department'     => $student->department?->name,
                'program'        => $student->program?->name,
                'academic_level' => $student->academic_level,
            ],
            'academic_session' => $registrations['registrations']->first()?->academicSession?->name,
            'semester'         => $registrations['registrations']->first()?->semester?->name,
            'courses'          => $registrations['registrations']->map(fn($r) => $r->course->only(['code', 'title', 'units']))->values(),
            'total_courses'    => $registrations['total_courses'],
            'total_units'      => $registrations['total_units'],
        ];
    }
}
