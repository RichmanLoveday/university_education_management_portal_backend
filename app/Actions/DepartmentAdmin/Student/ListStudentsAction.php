<?php

namespace App\Actions\DepartmentAdmin\Student;

use App\Models\Student;
use Illuminate\Http\Request;

class ListStudentsAction
{
    public function execute(Request $request, int $departmentId)
    {
        return Student::query()
            ->where('department_id', $departmentId)
            ->with(['user:id,name,email,is_active', 'program:id,name,code'])
            ->when($request->search, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('matric_number', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($q2) use ($search) {
                          $q2->where('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            })
            ->when($request->program_id, fn($q) => $q->where('program_id', $request->program_id))
            ->when($request->academic_level, fn($q) => $q->where('academic_level', $request->academic_level))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate($request->per_page ?? 15);
    }
}
