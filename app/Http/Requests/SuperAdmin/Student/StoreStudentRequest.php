<?php

namespace App\Http\Requests\SuperAdmin\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // User fields
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email', 'unique:users,email'],
            'password'         => ['required', 'string', Password::min(8)],

            // Student fields
            'matric_number'   => ['required', 'string', 'max:50', 'unique:students,matric_number'],
            'department_id'    => ['required', 'exists:departments,id'],
            'program_id'       => ['required', 'exists:programs,id'],
            'academic_level'   => ['required', 'integer', 'in:100,200,300,400,500'],
            'entry_year'       => ['nullable', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
            'current_session_id' => ['nullable', 'exists:academic_sessions,id'],
            'status'           => ['sometimes', Rule::in(['active', 'graduated', 'suspended', 'withdrawn'])],
            'is_active'        => ['sometimes', 'boolean'],
        ];
    }
}
