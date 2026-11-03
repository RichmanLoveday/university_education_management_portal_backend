<?php

namespace App\Actions\SuperAdmin\Lecturer;

use App\Models\Lecturer;
use Illuminate\Http\Request;

class ListLecturersAction
{
    public function execute(Request $request)
    {
        // list lecturers with optional filters for search term, department, and active status
        return Lecturer::query()
            ->with(['user:id,name,email,is_active', 'department:id,name,code'])
            ->when($request->search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('staff_id', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q2) use ($search) {
                          $q2->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            })
            ->when($request->department_id, fn($q) => $q->where('department_id', $request->department_id))
            ->when($request->has('is_active'), fn($q) => $q->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN)))
            ->latest()
            ->paginate($request->per_page ?? 15);
    }
}