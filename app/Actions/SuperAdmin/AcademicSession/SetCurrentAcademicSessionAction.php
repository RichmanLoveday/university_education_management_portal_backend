<?php

namespace App\Actions\SuperAdmin\AcademicSession;

use App\Models\AcademicSession;
use Illuminate\Support\Facades\DB;

class SetCurrentAcademicSessionAction
{
    public function execute(AcademicSession $academicSession): AcademicSession
    {
        // Use a database transaction to ensure atomicity when setting the current academic session
       return DB::transaction(function () use ($academicSession) {
            AcademicSession::where('is_current', true)->update(['is_current' => false]);

            $academicSession->update(['is_current' => true]);

            return $academicSession->fresh();
        });
    }
}