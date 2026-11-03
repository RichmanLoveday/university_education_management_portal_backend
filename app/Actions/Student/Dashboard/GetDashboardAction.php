<?php

namespace App\Actions\Student\Dashboard;

use App\Models\Student;
use App\Services\Academic\AcademicPeriodService;
use App\Services\Result\GpaService;

class GetDashboardAction
{
    public function __construct(
        private AcademicPeriodService $periodService,
        private GpaService $gpaService
    ) {}

    public function execute(Student $student): array
    {
        $period  = $this->periodService->current();
        $history = $this->gpaService->academicHistory($student);

        $currentRegistrations = $student->courseRegistrations()
            ->where('academic_session_id', $period['academic_session']['id'] ?? null)
            ->where('semester_id', $period['semester']['id'] ?? null)
            ->where('status', 'registered')
            ->with('course:id,code,title,units')
            ->get();

        return [
            'profile' => $student->load([
                'user:id,name,email',
                'department:id,faculty_id,name,code',
                'department.faculty:id,name',
                'program:id,name,code',
            ]),
            'current_period'      => $period,
            'current_semester'    => [
                'registered_courses' => $currentRegistrations->count(),
                'registered_units'   => $currentRegistrations->sum('course.units'),
                'courses'            => $currentRegistrations->pluck('course'),
            ],
            'academic_standing'   => $history['cumulative'],
            'latest_semester'     => $history['semesters']->last(),
        ];
    }
}
