<?php

namespace App\Http\Requests\DepartmentAdmin\Course;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('course')->department_id === $this->user()->getDepartmentId();
    }

    public function rules(): array
    {
        return [
            'code'        => ['sometimes', 'required', 'string', 'max:50', Rule::unique('courses')->ignore($this->route('course'))],
            'title'       => ['sometimes', 'required', 'string', 'max:255'],
            'units'       => ['sometimes', 'required', 'integer', 'min:1', 'max:10'],
            'level'       => ['sometimes', 'required', 'integer', 'in:100,200,300,400,500'],
            'semester'    => ['sometimes', 'required', 'integer', 'in:1,2'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['sometimes', 'boolean'],
        ];
    }
}
