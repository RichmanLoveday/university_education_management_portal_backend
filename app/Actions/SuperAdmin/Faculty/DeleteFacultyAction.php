<?php

namespace App\Actions\SuperAdmin\Faculty;

use App\Models\Faculty;
use Illuminate\Validation\ValidationException;

class DeleteFacultyAction
{
    public function execute(Faculty $faculty): void
    {
        if ($faculty->departments()->exists()) {
            throw ValidationException::withMessages([
                'faculty' => ['Cannot delete faculty. It has associated departments.'],
            ]);
        }

        $faculty->delete();
    }
}
