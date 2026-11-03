<?php

namespace App\Actions\SuperAdmin\Semester;

use App\Models\Semester;
use Illuminate\Validation\ValidationException;

class DeleteSemesterAction
{
    public function execute(Semester $semester): void
    {
        // Check if the semester is the current semester
        if ($semester->is_current) {
            throw ValidationException::withMessages([
                'semester' => ['Cannot delete the current semester.'],
            ]);
        }

        // Optional: prevent deletion if there are course registrations or results
        if ($semester->courseRegistrations()->exists()) {
            throw ValidationException::withMessages([
                'semester' => ['Cannot delete semester. It has course registrations.'],
            ]);
        }

        $semester->delete();
    }
}