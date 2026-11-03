<?php

namespace App\Actions\DepartmentAdmin\Course;

use App\Models\Course;
use Illuminate\Http\Request;

class ListCoursesAction
{
    public function execute(Request $request, int $departmentId)
    {
        return Course::query()
            ->where('department_id', $departmentId)
            ->when($request->search, function ($q, $search) {
                $q->where(fn($q) => $q->where('code', 'like', "%{$search}%")->orWhere('title', 'like', "%{$search}%"));
            })
            ->when($request->level, fn($q) => $q->where('level', $request->level))
            ->when($request->semester, fn($q) => $q->where('semester', $request->semester))
            ->when($request->has('is_active'), fn($q) => $q->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN)))
            ->orderBy('level')
            ->orderBy('code')
            ->paginate($request->per_page ?? 15);
    }
}
