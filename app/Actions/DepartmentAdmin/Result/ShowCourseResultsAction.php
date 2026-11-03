<?php

namespace App\Actions\DepartmentAdmin\Result;

use App\Models\CourseAllocation;
use App\Services\Report\ReportService;
use Illuminate\Http\Request;

class ShowCourseResultsAction
{
    public function __construct(
        private ReportService $reportService
    ) {}

    public function execute(CourseAllocation $allocation, Request $request): array
    {
        $results = $allocation->resultsQuery()
            ->with([
                'courseRegistration:id,student_id',
                'courseRegistration.student:id,user_id,matric_number,academic_level',
                'courseRegistration.student.user:id,name',
                'approvedBy:id,name',
            ])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->get()
            ->sortBy('courseRegistration.student.matric_number')
            ->values();

        return [
            'allocation' => $allocation->load([
                'course:id,code,title,units,level',
                'lecturer.user:id,name,email',
                'academicSession:id,name',
                'semester:id,name',
            ]),
            'registered_students' => $allocation->registrationsQuery()->count(),
            'statistics'          => $this->reportService->resultStatistics($results),
            'results'             => $results,
        ];
    }
}
