<?php

namespace App\Actions\Student\Course;

use App\Models\Course;
use App\Models\CourseAllocation;
use App\Models\Student;
use App\Services\Academic\AcademicPeriodService;

class ListAvailableCoursesAction
{
    public function __construct(
        private AcademicPeriodService $periodService
    ) {}

    public function execute(Student $student): array
    {
        $session  = $this->periodService->currentSession();
        $semester = $this->periodService->currentSemester();

        $registeredIds = $student->courseRegistrations()
            ->where('academic_session_id', $session->id)
            ->where('semester_id', $semester->id)
            ->where('status', 'registered')
            ->pluck('course_id');

        $lecturers = CourseAllocation::query()
            ->where('academic_session_id', $session->id)
            ->where('semester_id', $semester->id)
            ->with('lecturer.user:id,name')
            ->get()
            ->mapWithKeys(fn($a) => [$a->course_id => $a->lecturer?->user?->name]);

        $courses = Course::query()
            ->availableFor($student, $semester->number)
            ->orderBy('code')
            ->get(['id', 'code', 'title', 'units', 'level', 'semester', 'description'])
            ->map(fn(Course $course) => [
                ...$course->toArray(),
                'lecturer'      => $lecturers[$course->id] ?? null,
                'is_registered' => $registeredIds->contains($course->id),
            ]);

        return [
            'academic_session' => $session->only(['id', 'name']),
            'semester'         => $semester->only(['id', 'name', 'number']),
            'unit_limits'      => [
                'min' => (int) config('uemp.min_registration_units'),
                'max' => (int) config('uemp.max_registration_units'),
            ],
            'registered_units' => $courses->where('is_registered', true)->sum('units'),
            'courses'          => $courses,
        ];
    }
}
