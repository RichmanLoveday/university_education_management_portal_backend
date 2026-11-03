<?php

namespace App\Http\Requests\DepartmentAdmin\Program;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'code'           => ['required', 'string', 'max:50', 'unique:programs,code'],
            'degree_type'    => ['nullable', 'string', 'max:50'],
            'duration_years' => ['nullable', 'integer', 'min:1', 'max:7'],
            'is_active'      => ['sometimes', 'boolean'],
        ];
    }
}
