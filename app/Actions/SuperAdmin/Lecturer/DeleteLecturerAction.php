<?php

namespace App\Actions\SuperAdmin\Lecturer;

use App\Models\Lecturer;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DeleteLecturerAction
{
    public function execute(Lecturer $lecturer): void
    {
        // Check if the lecturer has any course allocations before deleting
        if ($lecturer->courseAllocations()->exists()) {
            throw ValidationException::withMessages([
                'lecturer' => ['Cannot delete lecturer. They have course allocations.'],
            ]);
        }

        DB::transaction(function () use ($lecturer) {
            $user = $lecturer->user;
            $lecturer->delete();
            $user->delete();
        });
    }
}
