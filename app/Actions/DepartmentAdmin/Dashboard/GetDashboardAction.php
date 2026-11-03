<?php

namespace App\Actions\DepartmentAdmin\Dashboard;

use App\Models\Course;
use App\Models\CourseAllocation;
use App\Models\CourseRegistration;
use App\Models\Department;
use App\Models\Lecturer;
use App\Models\Program;
use App\Models\Result;
use App\Models\Student;
use App\Services\Academic\AcademicPeriodService;

class GetDashboardAction
{
    public function __construct(
        private AcademicPeriodService $periodService
    ) {}

    public function execute(int $departmentId): array
    {
        $period     = $this->periodService->current();
        $sessionId  = $period['academic_session']['id'] ?? null;
        $semesterId = $period['semester']['id'] ?? null;

        $currentResults = Result::query()
            ->whereHas('courseRegistration', fn($q) => $q
                ->where('academic_session_id', $sessionId)
                ->where('semester_id', $semesterId)
                ->whereHas('course', fn($q) => $q->where('department_id', $departmentId)));

        return [
            'department'     => Department::with('faculty:id,name,code')->find($departmentId, ['id', 'faculty_id', 'name', 'code']),
            'current_period' => $period,
            'totals'         => [
                'students'        => Student::where('department_id', $departmentId)->count(),
                'active_students' => Student::where('department_id', $departmentId)->where('status', 'active')->count(),
                'lecturers'       => Lecturer::where('department_id', $departmentId)->count(),
                'programs'        => Program::where('department_id', $departmentId)->count(),
                'courses'         => Course::where('department_id', $departmentId)->count(),
            ],
            'current_semester' => [
                'course_allocations' => CourseAllocation::where('academic_session_id', $sessionId)
                    ->where('semester_id', $semesterId)
                    ->whereHas('course', fn($q) => $q->where('department_id', $departmentId))
                    ->count(),
                'course_registrations' => CourseRegistration::where('academic_session_id', $sessionId)
                    ->where('semester_id', $semesterId)
                    ->where('status', 'registered')
                    ->whereHas('student', fn($q) => $q->where('department_id', $departmentId))
                    ->count(),
                'results_by_status' => collect(['draft' => 0, 'submitted' => 0, 'approved' => 0, 'rejected' => 0])
                    ->merge((clone $currentResults)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status')),
            ],
            'pending_approvals' => Result::where('status', 'submitted')
                ->whereHas('courseRegistration.course', fn($q) => $q->where('department_id', $departmentId))
                ->count(),
        ];
    }
}
