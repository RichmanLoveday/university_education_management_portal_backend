<?php

namespace App\Actions\DepartmentAdmin\Lecturer;

use App\Models\Lecturer;

class ShowLecturerAction
{
    public function execute(Lecturer $lecturer): Lecturer
    {
        return $lecturer->load([
            'user:id,name,email,is_active,last_login_at',
            'department:id,name,code',
            'courseAllocations.course:id,code,title,units,level',
            'courseAllocations.academicSession:id,name',
            'courseAllocations.semester:id,name',
        ]);
    }
}
