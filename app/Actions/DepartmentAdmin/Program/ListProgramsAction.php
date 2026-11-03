<?php

namespace App\Actions\DepartmentAdmin\Program;

use App\Models\Program;
use Illuminate\Http\Request;

class ListProgramsAction
{
    public function execute(Request $request, int $departmentId)
    {
        return Program::query()
            ->where('department_id', $departmentId)
            ->withCount('students')
            ->when($request->search, function ($q, $search) {
                $q->where(fn($q) => $q->where('name', 'like', "%{$search}%")->orWhere('code', 'like', "%{$search}%"));
            })
            ->when($request->has('is_active'), fn($q) => $q->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN)))
            ->latest()
            ->paginate($request->per_page ?? 15);
    }
}
