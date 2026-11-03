<?php

namespace App\Http\Controllers\DepartmentAdmin;

use App\Actions\DepartmentAdmin\Result\ApproveResultsAction;
use App\Actions\DepartmentAdmin\Result\ListResultSubmissionsAction;
use App\Actions\DepartmentAdmin\Result\RejectResultsAction;
use App\Actions\DepartmentAdmin\Result\ReopenResultsAction;
use App\Actions\DepartmentAdmin\Result\ShowCourseResultsAction;
use App\Actions\DepartmentAdmin\Result\ShowResultAction;
use App\Http\Requests\DepartmentAdmin\Result\ApproveResultsRequest;
use App\Http\Requests\DepartmentAdmin\Result\RejectResultsRequest;
use App\Models\CourseAllocation;
use App\Models\Result;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResultController extends DepartmentAdminController
{
    public function submissions(Request $request, ListResultSubmissionsAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Result submissions retrieved successfully',
            'data'    => $action->execute($request, $this->departmentId($request)),
        ]);
    }

    public function course(Request $request, CourseAllocation $course_allocation, ShowCourseResultsAction $action): JsonResponse
    {
        $this->ensureSameDepartment($request, $course_allocation->course->department_id);

        return response()->json([
            'message' => 'Course results retrieved successfully',
            'data'    => $action->execute($course_allocation, $request),
        ]);
    }

    public function show(Request $request, Result $result, ShowResultAction $action): JsonResponse
    {
        $this->ensureSameDepartment($request, $result->courseRegistration->course->department_id);

        return response()->json([
            'message' => 'Result retrieved successfully',
            'data'    => $action->execute($result),
        ]);
    }

    public function approve(ApproveResultsRequest $request, CourseAllocation $course_allocation, ApproveResultsAction $action): JsonResponse
    {
        $results = $action->execute($course_allocation, $request->user(), $request->validated());

        return response()->json([
            'message' => "{$results->count()} result(s) approved successfully",
            'data'    => ['approved_count' => $results->count(), 'result_ids' => $results->pluck('id')],
        ]);
    }

    public function reject(RejectResultsRequest $request, CourseAllocation $course_allocation, RejectResultsAction $action): JsonResponse
    {
        $results = $action->execute($course_allocation, $request->user(), $request->validated());

        return response()->json([
            'message' => "{$results->count()} result(s) returned to the lecturer for correction",
            'data'    => ['rejected_count' => $results->count(), 'result_ids' => $results->pluck('id')],
        ]);
    }

    public function reopen(RejectResultsRequest $request, CourseAllocation $course_allocation, ReopenResultsAction $action): JsonResponse
    {
        $results = $action->execute($course_allocation, $request->user(), $request->validated());

        return response()->json([
            'message' => "{$results->count()} result(s) reopened for editing",
            'data'    => ['reopened_count' => $results->count(), 'result_ids' => $results->pluck('id')],
        ]);
    }
}
