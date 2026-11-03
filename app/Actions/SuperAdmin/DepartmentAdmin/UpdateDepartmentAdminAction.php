<?php

namespace App\Actions\SuperAdmin\DepartmentAdmin;

use App\Models\DepartmentAdmin;
use Illuminate\Support\Facades\DB;

class UpdateDepartmentAdminAction
{
    public function execute(DepartmentAdmin $departmentAdmin, array $data): DepartmentAdmin
    {
        return DB::transaction(function () use ($departmentAdmin, $data) {
            $userData = collect($data)->only(['name', 'is_active'])->toArray();

            if (!empty($userData)) {
                $departmentAdmin->user->update($userData);
            }

            if (isset($data['department_id'])) {
                $departmentAdmin->update(['department_id' => $data['department_id']]);
            }

            return $departmentAdmin->fresh(['user:id,name,email,is_active', 'department:id,name,code']);
        });
    }
}
