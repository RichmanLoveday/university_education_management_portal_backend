<?php

namespace App\Actions\SuperAdmin\Department;

use App\Models\Department;

class ShowDepartmentAction
{
    public function execute(Department $department): Department
    {
        return $department->load(['faculty:id,name,code'])
            ->loadCount(['programs', 'students', 'lecturers', 'courses']);
    }
}
