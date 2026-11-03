<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Actions\SuperAdmin\AcademicSession\CreateAcademicSessionAction;
use App\Actions\SuperAdmin\AcademicSession\DeleteAcademicSessionAction;
use App\Actions\SuperAdmin\AcademicSession\ListAcademicSessionsAction;
use App\Actions\SuperAdmin\AcademicSession\SetCurrentAcademicSessionAction;
use App\Actions\SuperAdmin\AcademicSession\ShowAcademicSessionAction;
use App\Actions\SuperAdmin\AcademicSession\UpdateAcademicSessionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\AcademicSession\StoreAcademicSessionRequest;
use App\Http\Requests\SuperAdmin\AcademicSession\UpdateAcademicSessionRequest;
use App\Models\AcademicSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcademicSessionController extends Controller
{
    public function index(Request $request, ListAcademicSessionsAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Academic sessions retrieved successfully',
            'data'    => $action->execute($request),
        ]);
    }

    public function store(StoreAcademicSessionRequest $request, CreateAcademicSessionAction $action): JsonResponse
    {
        $session = $action->execute($request->validated());

        return response()->json([
            'message' => 'Academic session created successfully',
            'data'    => $session,
        ], 201);
    }

    public function show(AcademicSession $academic_session, ShowAcademicSessionAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Academic session retrieved successfully',
            'data'    => $action->execute($academic_session),
        ]);
    }

    public function update(UpdateAcademicSessionRequest $request, AcademicSession $academic_session, UpdateAcademicSessionAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Academic session updated successfully',
            'data'    => $action->execute($academic_session, $request->validated()),
        ]);
    }

    public function destroy(AcademicSession $academic_session, DeleteAcademicSessionAction $action): JsonResponse
    {
        $action->execute($academic_session);

        return response()->json([
            'message' => 'Academic session deleted successfully',
        ]);
    }

    public function setCurrent(AcademicSession $academic_session, SetCurrentAcademicSessionAction $action): JsonResponse
    {
        $session = $action->execute($academic_session);

        return response()->json([
            'message' => 'Academic session set as current successfully',
            'data'    => $session,
        ]);
    }
}