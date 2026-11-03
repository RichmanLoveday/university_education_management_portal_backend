<?php

namespace App\Actions\SuperAdmin\Student;

use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateStudentAction
{
    public function execute(Student $student, array $data): Student
    {
        // update the student and the associated user in a database transaction
        return DB::transaction(function () use ($student, $data) {
            $userData = [];

            if (isset($data['name'])) $userData['name'] = $data['name'];
            if (isset($data['email'])) $userData['email'] = $data['email'];
            if (isset($data['is_active'])) $userData['is_active'] = $data['is_active'];
            if (!empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }

            if (!empty($userData)) {
                $student->user->update($userData);
            }

            $student->update(collect($data)->only([
                'matric_number', 'department_id', 'program_id',
                'academic_level', 'entry_year', 'current_session_id', 'status'
            ])->toArray());

            return $student->fresh(['user', 'department', 'program']);
        });
    }
}