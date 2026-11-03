<?php

namespace App\Actions\SuperAdmin\Lecturer;

use App\Models\Lecturer;

class ShowLecturerAction
{
    public function execute(Lecturer $lecturer): Lecturer
    {
        return $lecturer->load([
            'user:id,name,email,is_active,last_login_at',
            'department:id,name,code',
        ]);
    }
}
