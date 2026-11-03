<?php

namespace App\Actions\SuperAdmin\Semester;

use App\Models\Semester;
use Illuminate\Support\Facades\DB;

class SetCurrentSemesterAction
{
    public function execute(Semester $semester): Semester
    {
        // Set the specified semester as the current semester, ensuring that any other current semester is unset
        return DB::transaction(function () use ($semester) {
            // Skip this semester itself, otherwise re-setting the current semester leaves none current
            Semester::where('is_current', true)->where('id', '!=', $semester->id)->update(['is_current' => false]);

            $semester->update(['is_current' => true]);

            return $semester->fresh(['academicSession:id,name']);
        });
    }
}