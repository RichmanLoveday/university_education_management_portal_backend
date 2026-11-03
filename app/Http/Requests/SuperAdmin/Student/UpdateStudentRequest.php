<?php

namespace App\Http\Requests\SuperAdmin\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $student = $this->route('student');

        return [
            'name'             => ['sometimes', 'required', 'string', 'max:255'],
            'email'            => ['sometimes', 'required', 'email', Rule::unique('users', 'email')->ignore($student->user_id)],
            'password'         => ['sometimes', 'nullable', 'string', Password::min(8)],

            'matric_number'   => ['sometimes', 'required', 'string', 'max:50', Rule::unique('students')->ignore($student->id)],
            'department_id'    => ['sometimes', 'required', 'exists:departments,id'],
            'program_id'       => ['sometimes', 'required', 'exists:programs,id'],
            'academic_level'   => ['sometimes', 'required', 'integer', 'in:100,200,300,400,500'],
            'entry_year'       => ['nullable', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
            'current_session_id' => ['nullable', 'exists:academic_sessions,id'],
            'status'           => ['sometimes', Rule::in(['active', 'graduated', 'suspended', 'withdrawn'])],
            'is_active'        => ['sometimes', 'boolean'],
        ];
    }
}
