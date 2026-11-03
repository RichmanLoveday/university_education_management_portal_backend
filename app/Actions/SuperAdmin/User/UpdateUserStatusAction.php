<?php

namespace App\Actions\SuperAdmin\User;

use App\Models\User;

class UpdateUserStatusAction
{
    public function execute(User $user, bool $isActive): User
    {
        $user->update(['is_active' => $isActive]);

        return $user->fresh();
    }
}
