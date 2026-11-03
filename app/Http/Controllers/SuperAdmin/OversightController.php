<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Actions\DepartmentAdmin\Result\ShowResultAction;
use App\Actions\SuperAdmin\AuditLog\ListAuditLogsAction;
use App\Actions\SuperAdmin\CourseRegistration\ListCourseRegistrationsAction;
use App\Actions\SuperAdmin\Result\ListResultsAction;
use App\Http\Controllers\Controller;
use App\Models\Result;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

// Read-only, university-wide monitoring of registrations, results and audit activity
class OversightController extends Controller
{
    public function courseRegistrations(Request $request, ListCourseRegistrationsAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Course registrations retrieved successfully',
            'data'    => $action->execute($request),
        ]);
    }

public function results(Request $request, ListResultsAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Results retrieved successfully',
            'data'    => $action->execute($request),
        ]);
    }

    public function showResult(Result $result, ShowResultAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Result retrieved successfully',
            'data'    => $action->execute($result),
        ]);
    }

    public function auditLogs(Request $request, ListAuditLogsAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Audit logs retrieved successfully',
            'data'    => $action->execute($request),
        ]);
    }
}
