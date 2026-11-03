<?php

namespace App\Services\Result;

use App\Models\CourseAllocation;
use App\Models\Result;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ResultApprovalService
{
    public function __construct(
        private ResultAuditService $auditService
    ) {}

    public function approve(CourseAllocation $allocation, User $user, ?array $resultIds = null, ?string $comment = null): Collection
    {
        return $this->transition($allocation, $user, $resultIds, ['submitted'], 'approved', [
            'status'           => 'approved',
            'approved_by'      => $user->id,
            'approved_at'      => now(),
            'rejection_reason' => null,
        ], $comment);
    }

    public function reject(CourseAllocation $allocation, User $user, string $reason, ?array $resultIds = null): Collection
    {
        return $this->transition($allocation, $user, $resultIds, ['submitted'], 'rejected', [
            'status'           => 'rejected',
            'approved_by'      => null,
            'approved_at'      => null,
            'rejection_reason' => $reason,
        ], $reason);
    }

    public function reopen(CourseAllocation $allocation, User $user, string $reason, ?array $resultIds = null): Collection
    {
        return $this->transition($allocation, $user, $resultIds, ['submitted', 'approved'], 'reopened', [
            // Back to draft so the lecturer can correct it
            'status'           => 'draft',
            'submitted_at'     => null,
            'approved_by'      => null,
            'approved_at'      => null,
            'rejection_reason' => $reason,
        ], $reason);
    }

    private function transition(
        CourseAllocation $allocation,
        User $user,
        ?array $resultIds,
        array $fromStatuses,
        string $auditAction,
        array $attributes,
        ?string $comment
    ): Collection {
        return DB::transaction(function () use ($allocation, $user, $resultIds, $fromStatuses, $auditAction, $attributes, $comment) {
            $results = $allocation->resultsQuery()
                ->whereIn('status', $fromStatuses)
                ->when($resultIds, fn($q) => $q->whereIn('id', $resultIds))
                ->with('lecturer:id,user_id')
                ->lockForUpdate()
                ->get();

            if ($results->isEmpty()) {
                throw ValidationException::withMessages([
                    'results' => ['No results with status [' . implode(', ', $fromStatuses) . '] were found for this course.'],
                ]);
            }

            // Separation of duties: nobody reviews results they entered themselves
            if ($results->contains(fn(Result $r) => $r->lecturer?->user_id === $user->id)) {
                throw ValidationException::withMessages([
                    'results' => ['You cannot review results that you entered yourself.'],
                ]);
            }

            foreach ($results as $result) {
                $old = $this->auditService->snapshot($result);
                $result->update($attributes);
                $this->auditService->log($result, $auditAction, $user, $old, $comment);
            }

            return $results;
        });
    }
}
