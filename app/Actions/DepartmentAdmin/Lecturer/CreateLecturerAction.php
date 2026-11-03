<?php

namespace App\Actions\DepartmentAdmin\Lecturer;

use App\Models\Lecturer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateLecturerAction
{
    public function execute(array $data, int $departmentId): Lecturer
    {
        return DB::transaction(function () use ($data, $departmentId) {
            $user = User::create([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'password'  => Hash::make($data['password']),
                'role'      => 'lecturer',
                'is_active' => $data['is_active'] ?? true,
            ]);

            return Lecturer::create([
                'user_id'       => $user->id,
                'staff_id'      => $data['staff_id'],
                'department_id' => $departmentId,
                'title'         => $data['title'] ?? null,
                'rank'          => $data['rank'] ?? null,
                'is_active'     => $data['is_active'] ?? true,
            ]);
        });
    }
}
