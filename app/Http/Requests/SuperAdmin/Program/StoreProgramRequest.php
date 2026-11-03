<?php

namespace App\Http\Requests\SuperAdmin\Program;

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
            'department_id'  => ['required', 'exists:departments,id'],
            'name'           => ['required', 'string', 'max:255'],
            'code'           => ['required', 'string', 'max:50', 'unique:programs,code'],
            'degree_type'    => ['nullable', 'string', 'max:50'],
            'duration_years' => ['nullable', 'integer', 'min:1', 'max:7'],
            'is_active'      => ['sometimes', 'boolean'],
        ];
    }
}
