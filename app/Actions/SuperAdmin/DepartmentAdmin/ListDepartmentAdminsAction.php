<?php

namespace App\Actions\SuperAdmin\DepartmentAdmin;

use App\Models\DepartmentAdmin;
use Illuminate\Http\Request;

class ListDepartmentAdminsAction
{
    public function execute(Request $request)
    {
        return DepartmentAdmin::query()
            ->with(['user:id,name,email,is_active,last_login_at', 'department:id,name,code'])
            ->when($request->department_id, fn($q) => $q->where('department_id', $request->department_id))
            ->when($request->search, fn($q, $search) => $q->whereHas('user', fn($q2) => $q2
                ->where(fn($q3) => $q3->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))))
            ->latest()
            ->paginate($request->per_page ?? 15);
    }
}
