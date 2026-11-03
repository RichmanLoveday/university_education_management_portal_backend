<?php

namespace App\Http\Requests\SuperAdmin\Lecturer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreLecturerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'unique:users,email'],
            'password'      => ['required', 'string', Password::min(8)],
            'staff_id'      => ['required', 'string', 'max:50', 'unique:lecturers,staff_id'],
            'department_id' => ['required', 'exists:departments,id'],
            'title'         => ['nullable', 'string', 'max:50'],
            'rank'          => ['nullable', 'string', 'max:100'],
            'is_active'     => ['sometimes', 'boolean'],
        ];
    }
}
