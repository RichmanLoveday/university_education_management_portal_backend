<?php

namespace App\Http\Requests\DepartmentAdmin\Result;

use Illuminate\Foundation\Http\FormRequest;

class ApproveResultsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('course_allocation')->course->department_id === $this->user()->getDepartmentId();
    }

    public function rules(): array
    {
        return [
            // Omit result_ids to approve every submitted result of the course
            'result_ids'   => ['sometimes', 'array', 'min:1'],
            'result_ids.*' => ['integer', 'distinct', 'exists:results,id'],
            'comment'      => ['nullable', 'string', 'max:1000'],
        ];
    }
}
