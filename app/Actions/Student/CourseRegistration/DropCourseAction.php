<?php

namespace App\Actions\Student\CourseRegistration;

use App\Models\CourseRegistration;
use App\Services\Academic\AcademicPeriodService;
use Illuminate\Validation\ValidationException;

class DropCourseAction
{
    public function __construct(
        private AcademicPeriodService $periodService
    ) {}

    public function execute(CourseRegistration $registration): void
    {
        $semester = $this->periodService->currentSemester();

        if ($registration->semester_id !== $semester->id || $registration->status !== 'registered') {
            throw ValidationException::withMessages([
                'registration' => ['Only active registrations in the current semester can be dropped.'],
            ]);
        }

        if ($registration->result()->exists()) {
            throw ValidationException::withMessages([
                'registration' => ['This course cannot be dropped because a result has already been recorded for it.'],
            ]);
        }

        $registration->update(['status' => 'dropped']);
    }
}
