<?php

namespace App\Actions\SuperAdmin\Program;

use App\Models\Program;
use Illuminate\Http\Request;

class ListProgramsAction
{
    public function execute(Request $request)
    {
        // list programs with optional filters for department, search term, and active status
        return Program::query()
            ->with('department:id,name,code')
            ->when($request->search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            })
            ->when($request->department_id, fn($q) => $q->where('department_id', $request->department_id))
            ->when($request->has('is_active'), fn($q) => $q->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN)))
            ->withCount('students')
            ->latest()
            ->paginate($request->per_page ?? 15);
    }
}