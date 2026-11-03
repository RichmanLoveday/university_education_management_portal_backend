<?php

namespace App\Actions\DepartmentAdmin\Program;

use App\Models\Program;
use Illuminate\Validation\ValidationException;

class DeleteProgramAction
{
    public function execute(Program $program): void
    {
        if ($program->students()->exists()) {
            throw ValidationException::withMessages([
                'program' => ['Cannot delete program. It has associated students.'],
            ]);
        }

        $program->delete();
    }
}
