<?php

namespace App\Http\Controllers\DepartmentAdmin;

use App\Actions\DepartmentAdmin\Dashboard\GetDashboardAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends DepartmentAdminController
{
    public function index(Request $request, GetDashboardAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Dashboard retrieved successfully',
            'data'    => $action->execute($this->departmentId($request)),
        ]);
    }
}
