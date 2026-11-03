<?php

namespace App\Http\Requests\SuperAdmin\Faculty;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFacultyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['sometimes', 'required', 'string', 'max:255'],
            'code'        => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('faculties', 'code')->ignore($this->route('faculty')),
            ],
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
