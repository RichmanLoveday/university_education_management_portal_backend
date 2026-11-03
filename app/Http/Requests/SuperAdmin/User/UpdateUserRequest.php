<?php

namespace App\Http\Requests\SuperAdmin\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => ['sometimes', 'required', 'string', 'max:255'],
            'email'     => ['sometimes', 'required', 'email', 'max:255', Rule::unique('users')->ignore($this->route('user'))],
            'password'  => ['sometimes', 'nullable', 'string', Password::min(8)],
            'role'      => ['sometimes', 'required', Rule::in(['student', 'lecturer', 'department_admin', 'super_admin'])],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
