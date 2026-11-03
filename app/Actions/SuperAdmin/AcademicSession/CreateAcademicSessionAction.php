<?php

namespace App\Actions\SuperAdmin\AcademicSession;

use App\Models\AcademicSession;
use Illuminate\Support\Facades\DB;

class CreateAcademicSessionAction
{
    public function execute(array $data): AcademicSession
    {
        // Use a database transaction to ensure atomicity when creating a new academic session and updating the current session if needed
        return DB::transaction(function () use ($data) {
            if (!empty($data['is_current']) && $data['is_current'] === true) {
                AcademicSession::where('is_current', true)->update(['is_current' => false]);
            }

            return AcademicSession::create($data);
        });
    }
}