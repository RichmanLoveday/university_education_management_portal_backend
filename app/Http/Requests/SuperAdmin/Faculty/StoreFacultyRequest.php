<?php

namespace App\Http\Requests\SuperAdmin\Faculty;

use Illuminate\Foundation\Http\FormRequest;

class StoreFacultyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Already protected by role middleware
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'code'        => ['required', 'string', 'max:50', 'unique:faculties,code'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'Faculty name is required.',
            'code.required'  => 'Faculty code is required.',
            'code.unique'    => 'This faculty code already exists.',
        ];
    }
}
