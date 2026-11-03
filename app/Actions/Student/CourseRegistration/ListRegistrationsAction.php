<?php

namespace App\Actions\Student\CourseRegistration;

use App\Models\Student;
use App\Services\Academic\AcademicPeriodService;
use Illuminate\Http\Request;

class ListRegistrationsAction
{
    public function __construct(
        private AcademicPeriodService $periodService
    ) {}

    public function execute(Request $request, Student $student): array
    {
        $sessionId  = $request->integer('academic_session_id') ?: $this->periodService->currentSession()->id;
        $semesterId = $request->integer('semester_id') ?: $this->periodService->currentSemester()->id;

        $registrations = $student->courseRegistrations()
            ->where('academic_session_id', $sessionId)
            ->where('semester_id', $semesterId)
            ->where('status', 'registered')
            ->with(['course:id,code,title,units,level', 'academicSession:id,name', 'semester:id,name'])
            ->get()
            ->sortBy('course.code')
            ->values();

        return [
            'academic_session_id' => $sessionId,
            'semester_id'         => $semesterId,
            'total_courses'       => $registrations->count(),
            'total_units'         => $registrations->sum('course.units'),
            'registrations'       => $registrations,
        ];
    }
}
