<?php

namespace App\Actions\SuperAdmin\User;

use App\Models\User;

class UpdateUserStatusAction
{
    public function execute(User $user, bool $isActive): User
    {
        $user->update(['is_active' => $isActive]);

        // Revoke all tokens so a deactivated user is logged out immediately
        if (!$isActive) {
            $user->tokens()->delete();
        }

        return $user->fresh();
    }
}
