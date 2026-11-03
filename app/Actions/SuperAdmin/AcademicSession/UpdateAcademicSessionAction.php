<?php

namespace App\Actions\SuperAdmin\AcademicSession;

use App\Models\AcademicSession;
use Illuminate\Support\Facades\DB;

class UpdateAcademicSessionAction
{
    public function execute(AcademicSession $academicSession, array $data): AcademicSession
    {
        // Use a database transaction to ensure atomicity when updating an academic session and managing the current session status
        return DB::transaction(function () use ($academicSession, $data) {
            if (isset($data['is_current']) && $data['is_current'] === true) {
                AcademicSession::where('is_current', true)
                    ->where('id', '!=', $academicSession->id)
                    ->update(['is_current' => false]);
            }

            $academicSession->update($data);

            return $academicSession->fresh();
        });
    }
}
