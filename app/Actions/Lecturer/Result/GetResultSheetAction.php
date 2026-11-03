<?php

namespace App\Actions\Lecturer\Result;

use App\Models\CourseAllocation;

class GetResultSheetAction
{
    public function execute(CourseAllocation $allocation): array
    {
        $rows = $allocation->registrationsQuery()
            ->with([
                'student:id,user_id,matric_number,academic_level',
                'student.user:id,name',
                'result',
            ])
            ->get()
            ->sortBy('student.matric_number')
            ->values()
            ->map(fn($registration) => [
                'course_registration_id' => $registration->id,
                'matric_number'          => $registration->student->matric_number,
                'name'                   => $registration->student->user?->name,
                'result'                 => $registration->result?->only([
                    'id', 'ca_score', 'exam_score', 'total_score', 'grade', 'grade_point',
                    'status', 'submitted_at', 'approved_at', 'rejection_reason',
                ]),
                'is_editable'            => !$registration->result || in_array($registration->result->status, ['draft', 'rejected']),
            ]);

        return [
            'allocation' => $allocation->load(['course:id,code,title,units', 'academicSession:id,name', 'semester:id,name']),
            'score_limits' => [
                'max_ca_score'   => (float) config('uemp.max_ca_score'),
                'max_exam_score' => (float) config('uemp.max_exam_score'),
            ],
            'results' => $rows,
        ];
    }
}
