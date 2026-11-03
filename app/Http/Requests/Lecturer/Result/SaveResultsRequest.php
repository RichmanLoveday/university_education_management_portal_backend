<?php

namespace App\Http\Requests\Lecturer\Result;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveResultsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('course_allocation')->lecturer_id === $this->user()->lecturer?->id;
    }

    public function rules(): array
    {
        $allocation = $this->route('course_allocation');

        return [
            'results'                          => ['required', 'array', 'min:1'],
            // The registration must belong to THIS course, session and semester
            'results.*.course_registration_id' => [
                'required', 'integer', 'distinct',
                Rule::exists('course_registrations', 'id')
                    ->where('course_id', $allocation->course_id)
                    ->where('academic_session_id', $allocation->academic_session_id)
                    ->where('semester_id', $allocation->semester_id)
                    ->where('status', 'registered')
                    ->whereNull('deleted_at'),
            ],
            'results.*.ca_score'   => ['nullable', 'numeric', 'min:0', 'max:' . config('uemp.max_ca_score')],
            'results.*.exam_score' => ['nullable', 'numeric', 'min:0', 'max:' . config('uemp.max_exam_score')],
            'results.*.comment'    => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'results.*.course_registration_id.exists' => 'One or more students are not registered for this course.',
            'results.*.ca_score.max'   => 'CA score cannot exceed ' . config('uemp.max_ca_score') . '.',
            'results.*.exam_score.max' => 'Exam score cannot exceed ' . config('uemp.max_exam_score') . '.',
        ];
    }
}
