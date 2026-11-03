<?php

namespace App\Actions\Student\Profile;

use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class UpdateProfileAction
{
    public function execute(Student $student, array $data): Student
    {
        // Academic fields are admin-managed; students can only change their name and password
        $userData = collect($data)->only(['name'])->toArray();

        if (!empty($data['password'])) {
            $userData['password'] = Hash::make($data['password']);
        }

        if (!empty($userData)) {
            $student->user->update($userData);
        }

        return $student->fresh(['user:id,name,email']);
    }
}
