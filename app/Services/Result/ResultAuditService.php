<?php

namespace App\Services\Result;

use App\Models\Result;
use App\Models\ResultAudit;
use App\Models\User;

class ResultAuditService
{
    // Fields tracked in the audit trail
    private const TRACKED = [
        'ca_score', 'exam_score', 'total_score', 'grade', 'grade_point', 'status', 'rejection_reason',
    ];

    public function log(Result $result, string $action, User $performedBy, ?array $oldValues = null, ?string $comment = null): ResultAudit
    {
        return ResultAudit::create([
            'result_id'    => $result->id,
            'action'       => $action,
            'performed_by' => $performedBy->id,
            'old_values'   => $oldValues,
            'new_values'   => $this->snapshot($result),
            'comment'      => $comment,
            'created_at'   => now(),
        ]);
    }

    public function snapshot(Result $result): array
    {
        return collect($result->only(self::TRACKED))
            ->map(fn($value) => $value instanceof \Stringable ? (string) $value : $value)
            ->toArray();
    }
}
