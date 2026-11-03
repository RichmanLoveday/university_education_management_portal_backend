<?php

namespace App\Actions\DepartmentAdmin\Lecturer;

use App\Models\Lecturer;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DeleteLecturerAction
{
    public function execute(Lecturer $lecturer): void
    {
        if ($lecturer->courseAllocations()->exists() || $lecturer->results()->exists()) {
            throw ValidationException::withMessages([
                'lecturer' => ['Cannot delete lecturer. They have course allocations or results.'],
            ]);
        }

        DB::transaction(function () use ($lecturer) {
            $user = $lecturer->user;
            $lecturer->delete();
            $user->delete();
        });
    }
}
