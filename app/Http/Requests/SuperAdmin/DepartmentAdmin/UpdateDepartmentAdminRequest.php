<?php

namespace App\Http\Requests\SuperAdmin\DepartmentAdmin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['sometimes', 'required', 'string', 'max:255'],
            'department_id' => ['sometimes', 'required', 'exists:departments,id'],
            'is_active'     => ['sometimes', 'boolean'],
        ];
    }
}
