<?php

namespace App\Actions\Lecturer\Result;

use App\Models\CourseAllocation;
use App\Models\CourseRegistration;
use App\Models\Lecturer;
use App\Models\Result;
use App\Models\User;
use App\Services\Result\GradingService;
use App\Services\Result\ResultAuditService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaveResultsAction
{
    public function __construct(
        private GradingService $gradingService,
        private ResultAuditService $auditService
    ) {}

    public function execute(CourseAllocation $allocation, Lecturer $lecturer, User $user, array $rows): Collection
    {
        return DB::transaction(function () use ($allocation, $lecturer, $user, $rows) {
            $registrations = CourseRegistration::with('result')
                ->whereIn('id', collect($rows)->pluck('course_registration_id'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            // Submitted or approved results are locked until the department reopens them
            $locked = $registrations->filter(fn($r) => $r->result && !in_array($r->result->status, ['draft', 'rejected']));

            if ($locked->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'results' => ['Some results have already been submitted or approved and can no longer be edited: registration IDs ' . $locked->keys()->implode(', ') . '. Ask your department administrator to reopen them.'],
                ]);
            }

            $saved = collect();

            foreach ($rows as $row) {
                $registration = $registrations[$row['course_registration_id']];
                $ca   = isset($row['ca_score']) ? (float) $row['ca_score'] : null;
                $exam = isset($row['exam_score']) ? (float) $row['exam_score'] : null;

                $attributes = [
                    'ca_score'    => $ca,
                    'exam_score'  => $exam,
                    // Total, grade and grade point are always computed by the server
                    ...$this->gradingService->compute($ca, $exam),
                    'lecturer_id' => $lecturer->id,
                    'status'      => 'draft',
                ];

                if ($result = $registration->result) {
                    $old = $this->auditService->snapshot($result);
                    $result->fill($attributes);

                    if ($result->isDirty()) {
                        $result->save();
                        $this->auditService->log($result, 'updated', $user, $old, $row['comment'] ?? null);
                    }
                } else {
                    $result = Result::create(['course_registration_id' => $registration->id, ...$attributes]);
                    $this->auditService->log($result, 'created', $user, null, $row['comment'] ?? null);
                }

                $saved->push($result->fresh());
            }

            return $saved;
        });
    }
}
