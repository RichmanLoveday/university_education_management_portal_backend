<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\Report\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $reportService
    ) {}

    public function students(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Student report generated successfully',
            'data'    => $this->reportService->studentReport($request->integer('department_id') ?: null),
        ]);
    }

    public function courseRegistrations(Request $request): JsonResponse
    {
        return response()->json([
            'message' => 'Course registration report generated successfully',
            'data'    => $this->reportService->courseRegistrationReport(
                $request->integer('department_id') ?: null,
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
                $request->integer('department_id') ?: null,
                $request->integer('academic_session_id') ?: null,
                $request->integer('semester_id') ?: null,
            ),
        ]);
    }
}
