<?php

namespace App\Http\Requests\SuperAdmin\Semester;

use Illuminate\Foundation\Http\FormRequest;

class StoreSemesterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'academic_session_id' => ['required', 'exists:academic_sessions,id'],
            'name'                => ['required', 'string', 'max:100'],
            'number'              => [
                'required', 'integer', 'in:1,2',
                \Illuminate\Validation\Rule::unique('semesters')->where('academic_session_id', $this->academic_session_id)->whereNull('deleted_at'),
            ],
            'start_date'          => ['nullable', 'date'],
            'end_date'            => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_current'          => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'academic_session_id.required' => 'Academic session is required.',
            'academic_session_id.exists'   => 'Selected academic session does not exist.',
            'name.required'                => 'Semester name is required.',
            'number.unique'                => 'This semester number already exists for the selected session.',
            'end_date.after_or_equal'      => 'End date must be after or equal to start date.',
        ];
    }
}
