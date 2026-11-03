<?php

namespace App\Actions\DepartmentAdmin\Lecturer;

use App\Models\Lecturer;
use Illuminate\Support\Facades\DB;

class UpdateLecturerAction
{
    public function execute(Lecturer $lecturer, array $data): Lecturer
    {
        // Department is never changed by a department admin
        return DB::transaction(function () use ($lecturer, $data) {
            if (isset($data['is_active'])) {
                $lecturer->user->update(['is_active' => $data['is_active']]);
            }

            $lecturer->update(collect($data)->only(['staff_id', 'title', 'rank', 'is_active'])->toArray());

            return $lecturer->fresh(['user', 'department']);
        });
    }
}
