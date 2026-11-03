<?php

namespace App\Actions\Student\Profile;

use App\Models\Student;

class ShowProfileAction
{
    public function execute(Student $student): Student
    {
        return $student->load([
            'user:id,name,email,last_login_at',
            'department:id,faculty_id,name,code',
            'department.faculty:id,name,code',
            'program:id,name,code,degree_type,duration_years',
            'currentSession:id,name',
        ]);
    }
}
