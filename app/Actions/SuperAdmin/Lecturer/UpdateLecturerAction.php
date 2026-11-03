<?php

namespace App\Actions\SuperAdmin\Lecturer;

use App\Models\Lecturer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateLecturerAction
{
    public function execute(Lecturer $lecturer, array $data): Lecturer
    {
        // update the lecturer and associated user in a database transaction
        return DB::transaction(function () use ($lecturer, $data) {
            $userData = [];

            if (isset($data['name'])) $userData['name'] = $data['name'];
            if (isset($data['email'])) $userData['email'] = $data['email'];
            if (isset($data['is_active'])) $userData['is_active'] = $data['is_active'];
            if (!empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }

            if (!empty($userData)) {
                $lecturer->user->update($userData);
            }

            $lecturer->update(collect($data)->only([
                'staff_id', 'department_id', 'title', 'rank', 'is_active'
            ])->toArray());

            return $lecturer->fresh(['user', 'department']);
        });
    }
}
