<?php

namespace App\Actions\SuperAdmin\User;

use App\Models\User;

class UpdateUserRoleAction
{
    public function execute(User $user, string $role): User
    {
        $user->update(['role' => $role]);

        return $user->fresh();
    }
}
