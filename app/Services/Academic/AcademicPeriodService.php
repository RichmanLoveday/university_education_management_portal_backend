<?php

namespace App\Services\Academic;

use App\Models\AcademicSession;
use App\Models\Semester;
use Illuminate\Validation\ValidationException;

class AcademicPeriodService
{
    public function currentSession(): AcademicSession
    {
        $session = AcademicSession::where('is_current', true)->first();

        if (!$session) {
            throw ValidationException::withMessages([
                'academic_session' => ['No current academic session has been set. Please contact the administrator.'],
            ]);
        }

        return $session;
    }

    public function currentSemester(): Semester
    {
        $session = $this->currentSession();

        $semester = Semester::where('academic_session_id', $session->id)
            ->where('is_current', true)
            ->first();

        if (!$semester) {
            throw ValidationException::withMessages([
                'semester' => ['No current semester has been set for the current session. Please contact the administrator.'],
            ]);
        }

        return $semester;
    }

    // Non-throwing version used by dashboards
    public function current(): array
    {
        $session = AcademicSession::where('is_current', true)->first();

        $semester = $session
            ? Semester::where('academic_session_id', $session->id)->where('is_current', true)->first()
            : null;

        return [
            'academic_session' => $session?->only(['id', 'name']),
            'semester'         => $semester?->only(['id', 'name', 'number']),
        ];
    }
}
