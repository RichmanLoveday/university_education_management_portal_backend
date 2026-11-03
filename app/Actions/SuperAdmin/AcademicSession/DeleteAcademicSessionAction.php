<?php

namespace App\Actions\SuperAdmin\AcademicSession;

use App\Models\AcademicSession;
use Illuminate\Validation\ValidationException;

class DeleteAcademicSessionAction
{
    public function execute(AcademicSession $academicSession): void
    {
        if ($academicSession->is_current) {
            throw ValidationException::withMessages([
                'session' => ['Cannot delete the current academic session.'],
            ]);
        }

        if ($academicSession->semesters()->exists()) {
            throw ValidationException::withMessages([
                'session' => ['Cannot delete session. It has associated semesters.'],
            ]);
        }

        $academicSession->delete();
    }
}
