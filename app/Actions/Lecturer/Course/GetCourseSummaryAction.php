<?php

namespace App\Actions\Lecturer\Course;

use App\Models\CourseAllocation;
use App\Services\Report\ReportService;

class GetCourseSummaryAction
{
    public function __construct(
        private ReportService $reportService
    ) {}

    public function execute(CourseAllocation $allocation): array
    {
        $registered = $allocation->registrationsQuery()->count();
        $results    = $allocation->resultsQuery()->get();

        return [
            'course'              => $allocation->course->only(['id', 'code', 'title', 'units', 'level']),
            'registered_students' => $registered,
            'results_entered'     => $results->whereNotNull('total_score')->count(),
            'results_pending'     => max($registered - $results->whereNotNull('total_score')->count(), 0),
            'statistics'          => $this->reportService->resultStatistics($results),
        ];
    }
}
