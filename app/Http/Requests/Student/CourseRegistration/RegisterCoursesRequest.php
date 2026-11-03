<?php

namespace App\Http\Requests\Student\CourseRegistration;

use Illuminate\Foundation\Http\FormRequest;

class RegisterCoursesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // student_id is never accepted: a student can only register for themselves
        return [
            'course_ids'   => ['required', 'array', 'min:1'],
            'course_ids.*' => ['integer', 'distinct', 'exists:courses,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'course_ids.required' => 'Please select at least one course to register.',
            'course_ids.*.distinct' => 'The same course was selected more than once.',
        ];
    }
}
