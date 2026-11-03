<?php

namespace App\Http\Requests\DepartmentAdmin\Lecturer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLecturerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('lecturer')->department_id === $this->user()->getDepartmentId();
    }

    public function rules(): array
    {
        return [
            'staff_id'  => ['sometimes', 'required', 'string', 'max:50', Rule::unique('lecturers')->ignore($this->route('lecturer')->id)],
            'title'     => ['nullable', 'string', 'max:50'],
            'rank'      => ['nullable', 'string', 'max:100'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
