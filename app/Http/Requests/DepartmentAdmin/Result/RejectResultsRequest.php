<?php

namespace App\Http\Requests\DepartmentAdmin\Result;

use Illuminate\Foundation\Http\FormRequest;

class RejectResultsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('course_allocation')->course->department_id === $this->user()->getDepartmentId();
    }

    public function rules(): array
    {
        return [
            'result_ids'   => ['sometimes', 'array', 'min:1'],
            'result_ids.*' => ['integer', 'distinct', 'exists:results,id'],
            'reason'       => ['required', 'string', 'min:5', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required' => 'Please provide feedback explaining why the results are being returned.',
        ];
    }
}
