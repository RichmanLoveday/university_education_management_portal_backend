<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Actions\SuperAdmin\Dashboard\GetDashboardAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(GetDashboardAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Dashboard retrieved successfully',
            'data'    => $action->execute(),
        ]);
    }
}
