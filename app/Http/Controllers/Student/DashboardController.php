<?php

namespace App\Http\Controllers\Student;

use App\Actions\Student\Dashboard\GetDashboardAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends BaseStudentController
{
    public function index(Request $request, GetDashboardAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Dashboard retrieved successfully',
            'data'    => $action->execute($this->student($request)),
        ]);
    }
}
