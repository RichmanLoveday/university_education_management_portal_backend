<?php

namespace App\Actions\SuperAdmin\Program;

use App\Models\Program;

class UpdateProgramAction
{
    public function execute(Program $program, array $data): Program
    {
        $program->update($data);
        return $program->fresh(['department:id,name,code']);
    }
}
