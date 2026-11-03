<?php

namespace App\Actions\SuperAdmin\Course;

use App\Models\Course;
use Illuminate\Http\Request;

class ListCoursesAction
{
    public function execute(Request $request)
    {
        // Build the query to list courses with optional filters
        return Course::query()
            ->with('department:id,name,code')
            ->when($request->search, function ($q, $search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            })
            ->when($request->department_id, fn($q) => $q->where('department_id', $request->department_id))
            ->when($request->level, fn($q) => $q->where('level', $request->level))
            ->when($request->semester, fn($q) => $q->where('semester', $request->semester))
            ->when($request->has('is_active'), fn($q) => $q->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN)))
            ->latest()
            ->paginate($request->per_page ?? 15);
    }
}
