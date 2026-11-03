<?php

namespace App\Actions\Lecturer\Dashboard;

use App\Models\CourseAllocation;
use App\Models\Lecturer;
use App\Models\Result;
use App\Services\Academic\AcademicPeriodService;

class GetDashboardAction
{
    public function __construct(
        private AcademicPeriodService $periodService
    ) {}

    public function execute(Lecturer $lecturer): array
    {
        $period = $this->periodService->current();

        $currentAllocations = CourseAllocation::query()
            ->where('lecturer_id', $lecturer->id)
            ->where('academic_session_id', $period['academic_session']['id'] ?? null)
            ->where('semester_id', $period['semester']['id'] ?? null)
            ->with('course:id,code,title,units,level')
            ->get()
            ->map(fn(CourseAllocation $a) => [
                'course_allocation_id' => $a->id,
                'course'               => $a->course,
                'registered_students'  => $a->registrationsQuery()->count(),
                'result_counts'        => $a->resultsQuery()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            ]);

        return [
            'profile'        => $lecturer->load('user:id,name,email', 'department:id,name,code'),
            'current_period' => $period,
            'totals'         => [
                'assigned_courses_all_time'   => CourseAllocation::where('lecturer_id', $lecturer->id)->count(),
                'assigned_courses_current'    => $currentAllocations->count(),
                'students_current'            => $currentAllocations->sum('registered_students'),
            ],
            'results_by_status' => collect(['draft' => 0, 'submitted' => 0, 'approved' => 0, 'rejected' => 0])
                ->merge(Result::where('lecturer_id', $lecturer->id)->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status')),
            'rejected_results_needing_attention' => Result::where('lecturer_id', $lecturer->id)->where('status', 'rejected')->count(),
            'current_courses' => $currentAllocations,
        ];
    }
}
