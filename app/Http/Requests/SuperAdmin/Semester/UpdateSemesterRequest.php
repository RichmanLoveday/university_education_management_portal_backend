<?php

namespace App\Http\Requests\SuperAdmin\Semester;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSemesterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'academic_session_id' => ['sometimes', 'required', 'exists:academic_sessions,id'],
            'name'                => ['sometimes', 'required', 'string', 'max:100'],
            'number'              => [
                'sometimes', 'required', 'integer', 'in:1,2',
                \Illuminate\Validation\Rule::unique('semesters')
                    ->where('academic_session_id', $this->academic_session_id ?? $this->route('semester')->academic_session_id)
                    ->whereNull('deleted_at')
                    ->ignore($this->route('semester')),
            ],
            'start_date'          => ['nullable', 'date'],
            'end_date'            => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_current'          => ['sometimes', 'boolean'],
        ];
    }
}
