<?php

namespace App\Actions\SuperAdmin\Faculty;

use App\Models\Faculty;

class CreateFacultyAction
{
    public function execute(array $data): Faculty
    {
        return Faculty::create($data);
    }
}
