<?php

namespace App\Http\Controllers\Lecturer;

use App\Actions\Lecturer\Result\GetResultSheetAction;
use App\Actions\Lecturer\Result\SaveResultsAction;
use App\Actions\Lecturer\Result\ShowResultAction;
use App\Actions\Lecturer\Result\SubmitResultsAction;
use App\Http\Requests\Lecturer\Result\SaveResultsRequest;
use App\Http\Requests\Lecturer\Result\SubmitResultsRequest;
use App\Models\CourseAllocation;
use App\Models\Result;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResultController extends BaseLecturerController
{
    public function index(Request $request, CourseAllocation $course_allocation, GetResultSheetAction $action): JsonResponse
    {
        $this->ensureAssigned($request, $course_allocation);

        return response()->json([
            'message' => 'Result sheet retrieved successfully',
            'data'    => $action->execute($course_allocation),
        ]);
    }

    public function store(SaveResultsRequest $request, CourseAllocation $course_allocation, SaveResultsAction $action): JsonResponse
    {
        $results = $action->execute(
            $course_allocation,
            $this->lecturer($request),
            $request->user(),
            $request->validated('results')
        );

        return response()->json([
            'message' => "{$results->count()} result(s) saved as draft",
            'data'    => $results,
        ]);
    }

    public function submit(SubmitResultsRequest $request, CourseAllocation $course_allocation, SubmitResultsAction $action): JsonResponse
    {
        $results = $action->execute($course_allocation, $request->user(), $request->validated('result_ids'));

        return response()->json([
            'message' => "{$results->count()} result(s) submitted for departmental review",
            'data'    => ['submitted_count' => $results->count(), 'result_ids' => $results->pluck('id')],
        ]);
    }

    public function show(Request $request, Result $result, ShowResultAction $action): JsonResponse
    {
        abort_if($result->lecturer_id !== $this->lecturer($request)->id, 403, 'You can only view results you entered.');

        return response()->json([
            'message' => 'Result retrieved successfully',
            'data'    => $action->execute($result),
        ]);
    }
}
