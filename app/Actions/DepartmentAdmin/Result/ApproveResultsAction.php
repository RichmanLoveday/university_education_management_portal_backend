<?php

namespace App\Actions\DepartmentAdmin\Result;

use App\Models\CourseAllocation;
use App\Models\User;
use App\Services\Result\ResultApprovalService;
use Illuminate\Support\Collection;

class ApproveResultsAction
{
    public function __construct(
        private ResultApprovalService $approvalService
    ) {}

    public function execute(CourseAllocation $allocation, User $user, array $data): Collection
    {
        return $this->approvalService->approve($allocation, $user, $data['result_ids'] ?? null, $data['comment'] ?? null);
    }
}
