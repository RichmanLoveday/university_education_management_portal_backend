<?php

namespace App\Http\Controllers\Student;

use App\Actions\Student\Result\GetResultsAction;
use App\Actions\Student\Result\GetResultStatementAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResultController extends BaseStudentController
{
    public function index(Request $request, GetResultsAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Results retrieved successfully',
            'data'    => $action->execute($request, $this->student($request)),
        ]);
    }

    public function statement(Request $request, GetResultStatementAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Statement of result generated successfully',
            'data'    => $action->execute($request, $this->student($request)),
        ]);
    }
}
