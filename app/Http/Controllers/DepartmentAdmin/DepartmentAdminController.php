<?php

namespace App\Http\Controllers\DepartmentAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Department is always taken from the logged-in admin, never from the request
abstract class DepartmentAdminController extends Controller
{
    protected function departmentId(Request $request): int
    {
        $departmentId = $request->user()->getDepartmentId();

        abort_if(!$departmentId, 403, 'You are not assigned to any department. Please contact the Super Admin.');

        return $departmentId;
    }

    protected function ensureSameDepartment(Request $request, ?int $recordDepartmentId): void
    {
        abort_if(
            $recordDepartmentId !== $this->departmentId($request),
            403,
            'You can only manage records within your assigned department.'
        );
    }
}
