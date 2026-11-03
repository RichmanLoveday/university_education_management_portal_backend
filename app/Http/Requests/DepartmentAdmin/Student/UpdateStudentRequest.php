<?php

namespace App\Http\Requests\DepartmentAdmin\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Only students of the admin's own department may be updated
        return $this->route('student')->department_id === $this->user()->getDepartmentId();
    }

    public function rules(): array
    {
        $student      = $this->route('student');
        $departmentId = $this->user()->getDepartmentId();

        return [
            'matric_number'      => ['sometimes', 'required', 'string', 'max:50', Rule::unique('students')->ignore($student->id)],
            'program_id'         => ['sometimes', 'required', Rule::exists('programs', 'id')->where('department_id', $departmentId)],
            'academic_level'     => ['sometimes', 'required', 'integer', 'in:100,200,300,400,500'],
            'entry_year'         => ['nullable', 'integer', 'min:2000', 'max:' . (date('Y') + 1)],
            'current_session_id' => ['nullable', 'exists:academic_sessions,id'],
            'status'             => ['sometimes', Rule::in(['active', 'graduated', 'suspended', 'withdrawn'])],
            'is_active'          => ['sometimes', 'boolean'],
        ];
    }
}
