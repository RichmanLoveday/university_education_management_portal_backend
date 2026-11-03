<?php

namespace App\Actions\SuperAdmin\AuditLog;

use App\Models\ResultAudit;
use Illuminate\Http\Request;

class ListAuditLogsAction
{
    public function execute(Request $request)
    {
        return ResultAudit::query()
            ->with([
                'performedBy:id,name,email,role',
                'result:id,course_registration_id,status',
                'result.courseRegistration:id,student_id,course_id',
                'result.courseRegistration.course:id,code,title',
                'result.courseRegistration.student:id,matric_number',
            ])
            ->when($request->action, fn($q) => $q->where('action', $request->action))
            ->when($request->performed_by, fn($q) => $q->where('performed_by', $request->performed_by))
            ->when($request->result_id, fn($q) => $q->where('result_id', $request->result_id))
            ->when($request->from, fn($q) => $q->whereDate('created_at', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('created_at', '<=', $request->to))
            ->latest('created_at')
            ->paginate($request->per_page ?? 20);
    }
}
