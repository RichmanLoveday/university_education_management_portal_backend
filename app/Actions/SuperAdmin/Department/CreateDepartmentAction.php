<?php

namespace App\Actions\SuperAdmin\Department;

use App\Models\Department;

class CreateDepartmentAction
{
    public function execute(array $data): Department
    {
        return Department::create($data);
    }
}
