<?php

namespace App\Actions\SuperAdmin\AcademicSession;

use App\Models\AcademicSession;
use Illuminate\Http\Request;

class ListAcademicSessionsAction
{
    public function execute(Request $request)
    {
        // List all academic sessions with optional filter for current session
        return AcademicSession::query()
            ->withCount('semesters')
            ->when($request->has('is_current'), fn($q) => $q->where('is_current', filter_var($request->is_current, FILTER_VALIDATE_BOOLEAN)))
            ->latest()
            ->paginate($request->per_page ?? 15);
    }
}