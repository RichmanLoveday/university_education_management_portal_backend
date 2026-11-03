<?php

namespace App\Actions\SuperAdmin\User;

use App\Models\User;

class ShowUserAction
{
    public function execute(User $user): User
    {
        $user->load(['student.department', 'student.program', 'lecturer.department']);

        return $user;
    }
}