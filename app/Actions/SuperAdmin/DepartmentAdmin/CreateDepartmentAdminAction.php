<?php

namespace App\Actions\SuperAdmin\DepartmentAdmin;

use App\Models\DepartmentAdmin;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateDepartmentAdminAction
{
    public function execute(array $data): DepartmentAdmin
    {
        // Create the user account and assign it to a department in one transaction
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'password'  => Hash::make($data['password']),
                'role'      => 'department_admin',
                'is_active' => $data['is_active'] ?? true,
            ]);

            return DepartmentAdmin::create([
                'user_id'       => $user->id,
                'department_id' => $data['department_id'],
            ]);
        });
    }
}
