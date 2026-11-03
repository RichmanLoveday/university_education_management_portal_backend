<?php

namespace App\Http\Requests\DepartmentAdmin\CourseAllocation;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCourseAllocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('course_allocation')->course->department_id === $this->user()->getDepartmentId();
    }

    public function rules(): array
    {
        // Only re-assigning the lecturer is allowed; create a new allocation for another course/semester
        return [
            'lecturer_id' => [
                'required',
                Rule::exists('lecturers', 'id')
                    ->where('department_id', $this->user()->getDepartmentId())
                    ->where('is_active', true)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
