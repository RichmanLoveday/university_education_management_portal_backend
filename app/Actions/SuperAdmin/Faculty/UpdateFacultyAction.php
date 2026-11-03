<?php

namespace App\Actions\SuperAdmin\Faculty;

use App\Models\Faculty;

class UpdateFacultyAction
{
    public function execute(Faculty $faculty, array $data): Faculty
    {
        $faculty->update($data);

        return $faculty->fresh();
    }
}
