<?php

namespace App\Actions\SuperAdmin\Department;

use App\Models\Department;

class UpdateDepartmentAction
{
    public function execute(Department $department, array $data): Department
    {
        $department->update($data);
        return $department->fresh(['faculty:id,name,code']);
    }
}
