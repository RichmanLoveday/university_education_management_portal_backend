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
            'semester_id'          => ['required', Rule::exists('semesters', 'id')->where('academic_session_id', $this->academic_session_id)],
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

            $course   = \App\Models\Course::find($this->course_id);
            $semester = \App\Models\Semester::find($this->semester_id);

            // A first semester course cannot be allocated to a second semester
            if ($course && $semester && $course->semester !== $semester->number) {
                $validator->errors()->add('semester_id', 'This course is not offered in the selected semester.');
            }
        });
    }
}
