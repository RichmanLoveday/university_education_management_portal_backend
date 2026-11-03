<?php

namespace App\Http\Requests\DepartmentAdmin\Program;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('program')->department_id === $this->user()->getDepartmentId();
    }

    public function rules(): array
    {
        return [
            'name'           => ['sometimes', 'required', 'string', 'max:255'],
            'code'           => ['sometimes', 'required', 'string', 'max:50', Rule::unique('programs')->ignore($this->route('program'))],
            'degree_type'    => ['nullable', 'string', 'max:50'],
            'duration_years' => ['nullable', 'integer', 'min:1', 'max:7'],
            'is_active'      => ['sometimes', 'boolean'],
        ];
    }
}
