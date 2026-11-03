<?php

namespace App\Actions\SuperAdmin\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUserAction
{
    public function execute(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }
}
