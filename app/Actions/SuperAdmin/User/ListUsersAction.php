<?php

namespace App\Actions\SuperAdmin\User;

use App\Models\User;
use Illuminate\Http\Request;

class ListUsersAction
{
    public function execute(Request $request)
    {
        // Build a query to list users based on the provided filters in the request
        return User::query()
            ->when($request->search, function ($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->role, fn($q) => $q->where('role', $request->role))
            ->when($request->has('is_active'), function ($q) use ($request) {
                $q->where('is_active', filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN));
            })
            ->latest()
            ->paginate($request->per_page ?? 15);
    }
}
