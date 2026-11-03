<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Actions\SuperAdmin\GradingScale\SaveGradingScaleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\GradingScale\StoreGradingScaleRequest;
use App\Models\GradingScale;
use Illuminate\Http\JsonResponse;

class GradingScaleController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'message' => 'Grading scale retrieved successfully',
            'data'    => GradingScale::orderByDesc('min_score')->get(),
        ]);
    }

    public function store(StoreGradingScaleRequest $request, SaveGradingScaleAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Grade band created successfully',
            'data'    => $action->execute($request->validated()),
        ], 201);
    }

    public function show(GradingScale $grading_scale): JsonResponse
    {
        return response()->json([
            'message' => 'Grade band retrieved successfully',
            'data'    => $grading_scale,
        ]);
    }

    public function update(StoreGradingScaleRequest $request, GradingScale $grading_scale, SaveGradingScaleAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Grade band updated successfully',
            'data'    => $action->execute($request->validated(), $grading_scale),
        ]);
    }

    public function destroy(GradingScale $grading_scale): JsonResponse
    {
        $grading_scale->delete();

        return response()->json(['message' => 'Grade band deleted successfully']);
    }
}
