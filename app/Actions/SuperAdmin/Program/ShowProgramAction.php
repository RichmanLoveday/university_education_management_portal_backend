<?php

namespace App\Actions\SuperAdmin\Program;

use App\Models\Program;

class ShowProgramAction
{
    public function execute(Program $program): Program
    {
        return $program->load('department:id,name,code')->loadCount('students');
    }
}
