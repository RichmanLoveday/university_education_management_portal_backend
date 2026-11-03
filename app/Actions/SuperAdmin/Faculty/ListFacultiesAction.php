<?php

namespace App\Actions\SuperAdmin\Faculty;

use App\Models\Faculty;
use Illuminate\Http\Request;

class ListFacultiesAction
{
    public function execute(Request $request)
    {
        return Faculty::query()
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");
            })
            ->when($request->has('is_active'), function ($query) use ($request) {
                $query->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
            })
            ->withCount('departments')
            ->latest()
            ->paginate($request->per_page ?? 15);
    }
}