<?php

namespace App\Actions\SuperAdmin\Semester;

use App\Models\Semester;
use Illuminate\Support\Facades\DB;

class CreateSemesterAction
{
    public function execute(array $data): Semester
    {
        // create a new semester, ensuring that if it's marked as current, any other current semester is unset
        return DB::transaction(function () use ($data) {
            if (!empty($data['is_current']) && $data['is_current'] === true) {
                // Only one current semester allowed system-wide
                Semester::where('is_current', true)->update(['is_current' => false]);
            }

            return Semester::create($data);
        });
    }
}
