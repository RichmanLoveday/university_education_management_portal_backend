<?php

namespace App\Actions\SuperAdmin\Department;

use App\Models\Department;
use Illuminate\Http\Request;

class ListDepartmentsAction
{
    public function execute(Request $request)
    {
        // Fetch departments with optional search and filters
        return Department::query()
            ->with('faculty:id,name,code')
            ->when($request->search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            })
            ->when($request->faculty_id, fn($q) => $q->where('faculty_id', $request->faculty_id))
            ->when($request->has('is_active'), fn($q) => $q->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN)))
            ->withCount(['programs', 'students', 'lecturers', 'courses'])
            ->latest()
            ->paginate($request->per_page ?? 15);
    }
}
