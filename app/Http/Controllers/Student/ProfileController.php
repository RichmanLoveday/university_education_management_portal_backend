<?php

namespace App\Http\Controllers\Student;

use App\Actions\Student\Profile\ShowProfileAction;
use App\Actions\Student\Profile\UpdateProfileAction;
use App\Http\Requests\Student\Profile\UpdateProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends BaseStudentController
{
    public function show(Request $request, ShowProfileAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Profile retrieved successfully',
            'data'    => $action->execute($this->student($request)),
        ]);
    }

    public function update(UpdateProfileRequest $request, UpdateProfileAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Profile updated successfully',
            'data'    => $action->execute($this->student($request), $request->validated()),
        ]);
    }
}
