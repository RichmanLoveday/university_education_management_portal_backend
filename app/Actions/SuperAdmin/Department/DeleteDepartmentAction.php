<?php

namespace App\Actions\SuperAdmin\Department;

use App\Models\Department;
use Illuminate\Validation\ValidationException;

class DeleteDepartmentAction
{
    public function execute(Department $department): void
    {
    // Check if the department has any associated programs, students, or courses
        if ($department->programs()->exists() || $department->students()->exists() || $department->courses()->exists()) {
            throw ValidationException::withMessages([
                'department' => ['Cannot delete department. It has associated records.'],
            ]);
        }

        $department->delete();
    }
}
