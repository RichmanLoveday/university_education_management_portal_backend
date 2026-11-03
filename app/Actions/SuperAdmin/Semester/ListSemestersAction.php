<?php

namespace App\Actions\SuperAdmin\Semester;

use App\Models\Semester;
use Illuminate\Http\Request;

class ListSemestersAction
{
    public function execute(Request $request)
    {
        // list semesters with optional filters for academic session, search term, and current status
        return Semester::query()
            ->with('academicSession:id,name,is_current')
            ->when($request->academic_session_id, function ($q) use ($request) {
                $q->where('academic_session_id', $request->academic_session_id);
            })
            ->when($request->search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%");
            })
            ->when($request->has('is_current'), function ($q) use ($request) {
                $q->where('is_current', filter_var($request->is_current, FILTER_VALIDATE_BOOLEAN));
            })
            ->latest()
            ->paginate($request->per_page ?? 15);
    }
}