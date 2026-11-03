<?php

namespace App\Actions\Student\Result;

use App\Models\Student;
use App\Services\Result\GpaService;
use Illuminate\Http\Request;

class GetResultsAction
{
    public function __construct(
        private GpaService $gpaService
    ) {}

    public function execute(Request $request, Student $student): array
    {
        return $this->gpaService->academicHistory(
            $student,
            $request->integer('academic_session_id') ?: null,
            $request->integer('semester_id') ?: null,
        );
    }
}
