<?php

namespace App\Actions\SuperAdmin\DepartmentAdmin;

use App\Models\DepartmentAdmin;
use Illuminate\Support\Facades\DB;

class DeleteDepartmentAdminAction
{
    public function execute(DepartmentAdmin $departmentAdmin): void
    {
        DB::transaction(function () use ($departmentAdmin) {
            $user = $departmentAdmin->user;
            $user->tokens()->delete();
            $departmentAdmin->delete();
            $user->delete();
        });
    }
}
