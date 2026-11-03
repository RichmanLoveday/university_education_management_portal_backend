<?php

namespace App\Http\Requests\SuperAdmin\CourseAllocation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseAllocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id'            => ['sometimes', 'required', 'exists:courses,id'],
            'lecturer_id'          => ['sometimes', 'required', 'exists:lecturers,id'],
            'academic_session_id'  => ['sometimes', 'required', 'exists:academic_sessions,id'],
            'semester_id'          => ['sometimes', 'required', 'exists:semesters,id'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $allocation = $this->route('course_allocation');

            $exists = \App\Models\CourseAllocation::where('course_id', $this->course_id ?? $allocation->course_id)
                ->where('academic_session_id', $this->academic_session_id ?? $allocation->academic_session_id)
                ->where('semester_id', $this->semester_id ?? $allocation->semester_id)
                ->where('id', '!=', $allocation->id)
                ->exists();

            if ($exists) {
                $validator->errors()->add('course_id', 'This course has already been allocated for the selected session and semester.');
            }
        });
    }
}
