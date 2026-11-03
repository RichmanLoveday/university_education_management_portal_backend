<?php

namespace App\Http\Requests\DepartmentAdmin\Course;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'        => ['required', 'string', 'max:50', 'unique:courses,code'],
            'title'       => ['required', 'string', 'max:255'],
            'units'       => ['required', 'integer', 'min:1', 'max:10'],
            'level'       => ['required', 'integer', 'in:100,200,300,400,500'],
            'semester'    => ['required', 'integer', 'in:1,2'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['sometimes', 'boolean'],
        ];
    }
}
