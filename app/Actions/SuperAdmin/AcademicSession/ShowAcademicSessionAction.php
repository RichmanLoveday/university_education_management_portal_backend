<?php

namespace App\Actions\SuperAdmin\AcademicSession;

use App\Models\AcademicSession;

class ShowAcademicSessionAction
{
    public function execute(AcademicSession $academicSession): AcademicSession
    {
        return $academicSession->load(['semesters'])->loadCount('semesters');
    }
}
