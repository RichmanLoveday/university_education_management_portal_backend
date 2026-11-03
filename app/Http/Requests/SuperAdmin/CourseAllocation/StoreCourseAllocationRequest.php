<?php

namespace App\Http\Requests\SuperAdmin\CourseAllocation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourseAllocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id'            => ['required', 'exists:courses,id'],
            'lecturer_id'          => ['required', 'exists:lecturers,id'],
            'academic_session_id'  => ['required', 'exists:academic_sessions,id'],
            'semester_id'          => ['required', 'exists:semesters,id'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $exists = \App\Models\CourseAllocation::where('course_id', $this->course_id)
                ->where('academic_session_id', $this->academic_session_id)
                ->where('semester_id', $this->semester_id)
                ->exists();

            if ($exists) {
                $validator->errors()->add('course_id', 'This course has already been allocated for the selected session and semester.');
            }
        });
    }
}
