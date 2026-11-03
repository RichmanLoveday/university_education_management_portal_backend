<?php

namespace App\Actions\SuperAdmin\Semester;

use App\Models\Semester;

class ShowSemesterAction
{
    public function execute(Semester $semester): Semester
    {
        return $semester->load('academicSession:id,name,is_current');
    }
}
