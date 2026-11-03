<?php

namespace App\Actions\DepartmentAdmin\Student;

use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DeleteStudentAction
{
    public function execute(Student $student): void
    {
        if ($student->courseRegistrations()->exists()) {
            throw ValidationException::withMessages([
                'student' => ['Cannot delete student. They have course registrations.'],
            ]);
        }

        DB::transaction(function () use ($student) {
            $user = $student->user;
            $student->delete();
            $user->delete();
        });
    }
}
