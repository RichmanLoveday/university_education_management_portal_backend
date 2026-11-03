<?php

namespace App\Http\Requests\SuperAdmin\Lecturer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateLecturerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $lecturer = $this->route('lecturer');

        return [
            'name'          => ['sometimes', 'required', 'string', 'max:255'],
            'email'         => ['sometimes', 'required', 'email', Rule::unique('users', 'email')->ignore($lecturer->user_id)],
            'password'      => ['sometimes', 'nullable', 'string', Password::min(8)],
            'staff_id'      => ['sometimes', 'required', 'string', 'max:50', Rule::unique('lecturers')->ignore($lecturer->id)],
            'department_id' => ['sometimes', 'required', 'exists:departments,id'],
            'title'         => ['nullable', 'string', 'max:50'],
            'rank'          => ['nullable', 'string', 'max:100'],
            'is_active'     => ['sometimes', 'boolean'],
        ];
    }
}
