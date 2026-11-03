<?php

namespace App\Actions\Lecturer\Result;

use App\Models\CourseAllocation;
use App\Models\User;
use App\Services\Result\ResultAuditService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SubmitResultsAction
{
    public function __construct(
        private ResultAuditService $auditService
    ) {}

    public function execute(CourseAllocation $allocation, User $user, ?array $resultIds = null): Collection
    {
        return DB::transaction(function () use ($allocation, $user, $resultIds) {
            // Whole-course submission needs every registered student to have complete scores
            if (!$resultIds) {
                $missing = $allocation->registrationsQuery()
                    ->where(fn($q) => $q
                        ->whereDoesntHave('result')
                        ->orWhereHas('result', fn($q) => $q->whereNull('total_score')))
                    ->count();

                if ($missing > 0) {
                    throw ValidationException::withMessages([
                        'results' => ["{$missing} registered student(s) do not have complete CA and examination scores yet."],
                    ]);
                }
            }

            $results = $allocation->resultsQuery()
                ->whereIn('status', ['draft', 'rejected'])
                ->when($resultIds, fn($q) => $q->whereIn('id', $resultIds))
                ->lockForUpdate()
                ->get();

            if ($results->isEmpty()) {
                throw ValidationException::withMessages([
                    'results' => ['There are no draft results to submit for this course.'],
                ]);
            }

            if ($results->contains(fn($r) => $r->total_score === null)) {
                throw ValidationException::withMessages([
                    'results' => ['All selected results must have both CA and examination scores before submission.'],
                ]);
            }

            foreach ($results as $result) {
                $old = $this->auditService->snapshot($result);
                $result->update(['status' => 'submitted', 'submitted_at' => now()]);
                $this->auditService->log($result, 'submitted', $user, $old);
            }

            return $results;
        });
    }
}
