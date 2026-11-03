<?php

namespace App\Http\Requests\DepartmentAdmin\CourseAllocation;

use App\Models\Course;
use App\Models\CourseAllocation;
use App\Models\Semester;
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
        $departmentId = $this->user()->getDepartmentId();

        return [
            // Both the course and the lecturer must belong to the admin's department
            'course_id'           => ['required', Rule::exists('courses', 'id')->where('department_id', $departmentId)->whereNull('deleted_at')],
            'lecturer_id'         => ['required', Rule::exists('lecturers', 'id')->where('department_id', $departmentId)->where('is_active', true)->whereNull('deleted_at')],
            'academic_session_id' => ['required', 'exists:academic_sessions,id'],
            'semester_id'         => ['required', Rule::exists('semesters', 'id')->where('academic_session_id', $this->academic_session_id)],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $exists = CourseAllocation::where('course_id', $this->course_id)
                ->where('academic_session_id', $this->academic_session_id)
                ->where('semester_id', $this->semester_id)
                ->exists();

            if ($exists) {
                $validator->errors()->add('course_id', 'This course has already been allocated for the selected session and semester.');
            }

            if (Course::find($this->course_id)->semester !== Semester::find($this->semester_id)->number) {
                $validator->errors()->add('semester_id', 'This course is not offered in the selected semester.');
            }
        });
    }
}
