<?php

namespace App\Actions\SuperAdmin\Student;

use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateStudentAction
{
    public function execute(array $data): Student
    {
        // Create a new student along with the associated user in a database transaction
        return DB::transaction(function () use ($data) {
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
                'department_id'      => $data['department_id'],
                'program_id'         => $data['program_id'],
                'academic_level'     => $data['academic_level'],
                'entry_year'         => $data['entry_year'] ?? null,
                'current_session_id' => $data['current_session_id'] ?? null,
                'status'             => $data['status'] ?? 'active',
            ]);
        });
    }
}
