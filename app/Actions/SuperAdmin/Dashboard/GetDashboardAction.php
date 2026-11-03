<?php

namespace App\Actions\SuperAdmin\Dashboard;

use App\Models\Course;
use App\Models\CourseAllocation;
use App\Models\CourseRegistration;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Lecturer;
use App\Models\Program;
use App\Models\Result;
use App\Models\ResultAudit;
use App\Models\Student;
use App\Models\User;
use App\Services\Academic\AcademicPeriodService;

class GetDashboardAction
{
    public function __construct(
        private AcademicPeriodService $periodService
    ) {}

    public function execute(): array
    {
        $period     = $this->periodService->current();
        $sessionId  = $period['academic_session']['id'] ?? null;
        $semesterId = $period['semester']['id'] ?? null;

        return [
            'current_period' => $period,
            'totals' => [
                'faculties'   => Faculty::count(),
                'departments' => Department::count(),
                'programs'    => Program::count(),
                'courses'     => Course::count(),
                'students'    => Student::count(),
                'lecturers'   => Lecturer::count(),
                'users'       => User::count(),
                'inactive_users' => User::where('is_active', false)->count(),
            ],
            'users_by_role' => User::selectRaw('role, count(*) as total')->groupBy('role')->pluck('total', 'role'),
            'current_semester' => [
                'course_allocations'   => CourseAllocation::where('academic_session_id', $sessionId)->where('semester_id', $semesterId)->count(),
                'course_registrations' => CourseRegistration::where('academic_session_id', $sessionId)->where('semester_id', $semesterId)->where('status', 'registered')->count(),
                'students_registered'  => CourseRegistration::where('academic_session_id', $sessionId)->where('semester_id', $semesterId)->where('status', 'registered')->distinct('student_id')->count('student_id'),
            ],
            'results_by_status' => collect(['draft' => 0, 'submitted' => 0, 'approved' => 0, 'rejected' => 0])
                ->merge(Result::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status')),
            'departments' => Department::query()
                ->with('faculty:id,name,code')
                ->withCount(['students', 'lecturers', 'courses'])
                ->orderBy('name')
                ->get(['id', 'faculty_id', 'name', 'code']),
            'recent_activity' => ResultAudit::query()
                ->with(['performedBy:id,name,role', 'result:id,course_registration_id', 'result.courseRegistration:id,course_id', 'result.courseRegistration.course:id,code'])
                ->latest('created_at')
                ->limit(10)
                ->get(),
        ];
    }
}
