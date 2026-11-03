<?php

namespace App\Http\Controllers\Lecturer;

use App\Actions\Lecturer\Dashboard\GetDashboardAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends BaseLecturerController
{
    public function index(Request $request, GetDashboardAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Dashboard retrieved successfully',
            'data'    => $action->execute($this->lecturer($request)),
        ]);
    }
}
