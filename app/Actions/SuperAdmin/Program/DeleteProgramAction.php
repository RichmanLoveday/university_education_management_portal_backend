<?php

namespace App\Actions\SuperAdmin\Program;

use App\Models\Program;
use Illuminate\Validation\ValidationException;

class DeleteProgramAction
{
    public function execute(Program $program): void
    {
        // Check if the program has associated students
        if ($program->students()->exists()) {
            throw ValidationException::withMessages([
                'program' => ['Cannot delete program. It has associated students.'],
            ]);
        }

        $program->delete();
    }
}
