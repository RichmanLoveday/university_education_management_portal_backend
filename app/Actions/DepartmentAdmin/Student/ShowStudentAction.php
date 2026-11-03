<?php

namespace App\Actions\DepartmentAdmin\Student;

use App\Models\Student;

class ShowStudentAction
{
    public function execute(Student $student): Student
    {
        return $student->load([
            'user:id,name,email,is_active,last_login_at',
            'department:id,name,code',
            'program:id,name,code',
            'currentSession:id,name',
        ]);
    }
}
