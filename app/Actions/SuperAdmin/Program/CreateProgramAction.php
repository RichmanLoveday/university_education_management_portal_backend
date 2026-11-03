<?php

namespace App\Actions\SuperAdmin\Program;

use App\Models\Program;

class CreateProgramAction
{
    public function execute(array $data): Program
    {
        return Program::create($data);
    }
}
