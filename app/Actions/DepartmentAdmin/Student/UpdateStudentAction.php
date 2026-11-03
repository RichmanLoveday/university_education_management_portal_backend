<?php

namespace App\Actions\DepartmentAdmin\Student;

use App\Models\Student;
use Illuminate\Support\Facades\DB;

class UpdateStudentAction
{
    public function execute(Student $student, array $data): Student
    {
        // Department is never changed by a department admin
        return DB::transaction(function () use ($student, $data) {
            if (isset($data['is_active'])) {
                $student->user->update(['is_active' => $data['is_active']]);
            }

            $student->update(collect($data)->only([
                'matric_number', 'program_id', 'academic_level',
                'entry_year', 'current_session_id', 'status',
            ])->toArray());

            return $student->fresh(['user', 'program']);
        });
    }
}
