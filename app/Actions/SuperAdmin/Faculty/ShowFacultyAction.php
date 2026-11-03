<?php

namespace App\Actions\SuperAdmin\Faculty;

use App\Models\Faculty;

class ShowFacultyAction
{
    public function execute(Faculty $faculty): Faculty
    {
        $faculty->loadCount('departments');
        $faculty->load(['departments:id,faculty_id,name,code,is_active']);

        return $faculty;
    }
}
