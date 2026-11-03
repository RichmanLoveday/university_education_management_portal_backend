<?php

namespace App\Actions\DepartmentAdmin\Result;

use App\Models\Result;

class ShowResultAction
{
    public function execute(Result $result): Result
    {
        return $result->load([
            'courseRegistration.student.user:id,name,email',
            'courseRegistration.course:id,code,title,units,level,department_id',
            'courseRegistration.academicSession:id,name',
            'courseRegistration.semester:id,name',
            'lecturer.user:id,name',
            'approvedBy:id,name',
            'audits' => fn($q) => $q->latest('created_at'),
            'audits.performedBy:id,name,role',
        ]);
    }
}
