<?php

namespace App\Http\Controllers\DepartmentAdmin;

use App\Services\Report\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends DepartmentAdminController
{
    public function __construct(
        private ReportService $reportService
    ) {}

    public function students(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Student report generated successfully',
            'data'    => $this->reportService->studentReport($this->departmentId($request)),
        ]);
    }

    public function courseRegistrations(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Course registration report generated successfully',
            'data'    => $this->reportService->courseRegistrationReport(
                $this->departmentId($request),
                $request->integer('academic_session_id') ?: null,
                $request->integer('semester_id') ?: null,
            ),
        ]);
    }

    public function academicPerformance(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Academic performance report generated successfully',
            'data'    => $this->reportService->academicPerformanceReport(
                $this->departmentId($request),
                $request->integer('academic_session_id') ?: null,
                $request->integer('semester_id') ?: null,
            ),
        ]);
    }
}
