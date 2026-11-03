<?php

namespace App\Http\Requests\Lecturer\Result;

use Illuminate\Foundation\Http\FormRequest;

class SubmitResultsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('course_allocation')->lecturer_id === $this->user()->lecturer?->id;
    }

    public function rules(): array
    {
        return [
            'result_ids'   => ['sometimes', 'array', 'min:1'],
            'result_ids.*' => ['integer', 'distinct', 'exists:results,id'],
        ];
    }
}
