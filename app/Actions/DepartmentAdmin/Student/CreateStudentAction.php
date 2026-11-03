<?php

namespace App\Actions\DepartmentAdmin\Student;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateStudentAction
{
    public function execute(array $data, int $departmentId): Student
    {
        // Wrap the creation of the User and Student in a transaction to ensure atomicity
        return DB::transaction(function () use ($data, $departmentId) {
            $user = User::create([
                'name'      => $data['name'],
                'email'     => $data['email'],
                'password'  => Hash::make($data['password']),
                'role'      => 'student',
                'is_active' => $data['is_active'] ?? true,
            ]);

            return Student::create([
                'user_id'            => $user->id,
                'matric_number'     => $data['matric_number'],
                'department_id'      => $departmentId,
                'program_id'         => $data['program_id'],
                'academic_level'     => $data['academic_level'],
                'entry_year'         => $data['entry_year'] ?? null,
                'current_session_id' => $data['current_session_id'] ?? null,
                'status'             => $data['status'] ?? 'active',
            ]);
        });
    }
}