<?php

namespace App\Actions\DepartmentAdmin\Program;

use App\Models\Program;

class CreateProgramAction
{
    public function execute(array $data, int $departmentId): Program
    {
        return Program::create([...$data, 'department_id' => $departmentId]);
    }
}
