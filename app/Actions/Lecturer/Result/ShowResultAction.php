<?php

namespace App\Actions\Lecturer\Result;

use App\Models\Result;

class ShowResultAction
{
    public function execute(Result $result): Result
    {
        return $result->load([
            'courseRegistration.student:id,user_id,matric_number',
            'courseRegistration.student.user:id,name',
            'courseRegistration.course:id,code,title,units',
            'approvedBy:id,name',
            'audits' => fn($q) => $q->latest('created_at'),
            'audits.performedBy:id,name,role',
        ]);
    }
}
