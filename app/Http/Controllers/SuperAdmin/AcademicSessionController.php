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
    /*
     * Display a listing of the academic sessions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Actions\SuperAdmin\AcademicSession\ListAcademicSessionsAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, ListAcademicSessionsAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Academic sessions retrieved successfully',
            'data'    => $action->execute($request),
        ]);
    }

    /*
     * Store a newly created academic session in storage.
     *
     * @param  \App\Http\Requests\SuperAdmin\AcademicSession\StoreAcademicSessionRequest  $request
     * @param  \App\Actions\SuperAdmin\AcademicSession\CreateAcademicSessionAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreAcademicSessionRequest $request, CreateAcademicSessionAction $action): JsonResponse
    {
        $session = $action->execute($request->validated());

        return response()->json([
            'message' => 'Academic session created successfully',
            'data'    => $session,
        ], 201);
    }


    /*
     * Display the specified academic session.
     *
     * @param  \App\Models\AcademicSession  $academic_session
     * @param  \App\Actions\SuperAdmin\AcademicSession\ShowAcademicSessionAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(AcademicSession $academic_session, ShowAcademicSessionAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Academic session retrieved successfully',
            'data'    => $action->execute($academic_session),
        ]);
    }


    /*
     * Update the specified academic session in storage.
     *
     * @param  \App\Http\Requests\SuperAdmin\AcademicSession\UpdateAcademicSessionRequest  $request
     * @param  \App\Models\AcademicSession  $academic_session
     * @param  \App\Actions\SuperAdmin\AcademicSession\UpdateAcademicSessionAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateAcademicSessionRequest $request, AcademicSession $academic_session, UpdateAcademicSessionAction $action): JsonResponse
    {
        return response()->json([
            'message' => 'Academic session updated successfully',
            'data'    => $action->execute($academic_session, $request->validated()),
        ]);
    }

    /*
     * Remove the specified academic session from storage.
     *
     * @param  \App\Models\AcademicSession  $academic_session
     * @param  \App\Actions\SuperAdmin\AcademicSession\DeleteAcademicSessionAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(AcademicSession $academic_session, DeleteAcademicSessionAction $action): JsonResponse
    {
        $action->execute($academic_session);

        return response()->json([
            'message' => 'Academic session deleted successfully',
        ]);
    }

    /*
     * Set the specified academic session as the current session.
     *
     * @param  \App\Models\AcademicSession  $academic_session
     * @param  \App\Actions\SuperAdmin\AcademicSession\SetCurrentAcademicSessionAction  $action
     * @return \Illuminate\Http\JsonResponse
     */
    public function setCurrent(AcademicSession $academic_session, SetCurrentAcademicSessionAction $action): JsonResponse
    {
        $session = $action->execute($academic_session);

        return response()->json([
            'message' => 'Academic session set as current successfully',
            'data'    => $session,
        ]);
    }
}