<?php

namespace App\Actions\Student\Result;

use App\Models\Student;
use App\Services\Result\GpaService;
use App\Services\Result\GradingService;
use Illuminate\Http\Request;

class GetResultStatementAction
{
    public function __construct(
        private GpaService $gpaService,
        private GradingService $gradingService
    ) {}

    public function execute(Request $request, Student $student): array
    {
        $student->load(['user:id,name,email', 'department.faculty:id,name', 'program:id,name,degree_type']);

        return [
            'document'     => 'Statement of Result',
            'generated_at' => now()->toDateTimeString(),
            'student'      => [
                'name'           => $student->user->name,
                'matric_number'  => $student->matric_number,
                'faculty'        => $student->department?->faculty?->name,
                'department'     => $student->department?->name,
                'program'        => $student->program?->name,
                'academic_level' => $student->academic_level,
                'status'         => $student->status,
            ],
            ...$this->gpaService->academicHistory(
                $student,
                $request->integer('academic_session_id') ?: null,
                $request->integer('semester_id') ?: null,
            ),
            'grading_scale' => $this->gradingService->scale()->values(),
        ];
    }
}
