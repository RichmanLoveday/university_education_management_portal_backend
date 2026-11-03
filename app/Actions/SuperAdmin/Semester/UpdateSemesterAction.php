<?php

namespace App\Actions\SuperAdmin\Semester;

use App\Models\Semester;
use Illuminate\Support\Facades\DB;

class UpdateSemesterAction
{
    public function execute(Semester $semester, array $data): Semester
    {
        // update the semester, ensuring that if it's marked as current, any other current semester is unset
        return DB::transaction(function () use ($semester, $data) {
            if (isset($data['is_current']) && $data['is_current'] === true) {
                Semester::where('is_current', true)
                    ->where('id', '!=', $semester->id)
                    ->update(['is_current' => false]);
            }

            $semester->update($data);

            return $semester->fresh(['academicSession:id,name,is_current']);
        });
    }
}