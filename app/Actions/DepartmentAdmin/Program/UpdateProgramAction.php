<?php

namespace App\Actions\DepartmentAdmin\Program;

use App\Models\Program;

class UpdateProgramAction
{
    public function execute(Program $program, array $data): Program
    {
        $program->update(collect($data)->except('department_id')->toArray());

        return $program->fresh();
    }
}
