<?php

namespace App\Http\Requests\Student\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'             => ['sometimes', 'required', 'string', 'max:255'],
            'current_password' => ['required_with:password', 'current_password:sanctum'],
            'password'         => ['sometimes', 'required', 'string', 'confirmed', Password::min(8)],
        ];
    }
}
