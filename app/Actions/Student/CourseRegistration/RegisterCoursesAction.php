<?php

namespace App\Actions\Student\CourseRegistration;

use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Student;
use App\Services\Academic\AcademicPeriodService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RegisterCoursesAction
{
    public function __construct(
        private AcademicPeriodService $periodService
    ) {}

    public function execute(Student $student, array $courseIds): Collection
    {
        if ($student->status !== 'active') {
            throw ValidationException::withMessages([
                'student' => ["Only active students can register courses. Your status is '{$student->status}'."],
            ]);
        }

        $session  = $this->periodService->currentSession();
        $semester = $this->periodService->currentSemester();

        return DB::transaction(function () use ($student, $courseIds, $session, $semester) {
            // Rule 1: only courses for the student's department, level and the current semester
            $courses = Course::query()
                ->availableFor($student, $semester->number)
                ->whereIn('id', $courseIds)
                ->get();

            $ineligible = collect($courseIds)->diff($courses->pluck('id'));

            if ($ineligible->isNotEmpty()) {
                $codes = Course::whereIn('id', $ineligible)->pluck('code')->implode(', ');

                throw ValidationException::withMessages([
                    'course_ids' => ["The following courses are not available for your department, level or the current semester: {$codes}."],
                ]);
            }

            // Rule 2: no duplicate registration within the same semester
            $existing = CourseRegistration::withTrashed()
                ->where('student_id', $student->id)
                ->where('academic_session_id', $session->id)
                ->where('semester_id', $semester->id)
                ->whereIn('course_id', $courseIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('course_id');

            $duplicates = $existing->filter(fn($r) => $r->status === 'registered' && !$r->trashed());

            if ($duplicates->isNotEmpty()) {
                $codes = $courses->whereIn('id', $duplicates->keys())->pluck('code')->implode(', ');

                throw ValidationException::withMessages([
                    'course_ids' => ["You have already registered for: {$codes}."],
                ]);
            }

            // Rule 3: credit unit limit for the semester
            $currentUnits = CourseRegistration::query()
                ->where('student_id', $student->id)
                ->where('academic_session_id', $session->id)
                ->where('semester_id', $semester->id)
                ->where('status', 'registered')
                ->join('courses', 'courses.id', '=', 'course_registrations.course_id')
                ->sum('courses.units');

            $newTotal = $currentUnits + $courses->sum('units');
            $maxUnits = (int) config('uemp.max_registration_units');

            if ($newTotal > $maxUnits) {
                throw ValidationException::withMessages([
                    'course_ids' => ["Registration would bring your total to {$newTotal} units, which exceeds the maximum of {$maxUnits} units per semester."],
                ]);
            }

            return $courses->map(function (Course $course) use ($student, $session, $semester, $existing) {
                // Re-activate a previously dropped registration instead of breaking the unique index
                if ($previous = $existing->get($course->id)) {
                    $previous->restore();
                    $previous->update(['status' => 'registered', 'registration_date' => now()]);

                    return $previous->load('course:id,code,title,units,level');
                }

                return CourseRegistration::create([
                    'student_id'          => $student->id,
                    'course_id'           => $course->id,
                    'academic_session_id' => $session->id,
                    'semester_id'         => $semester->id,
                    'registration_date'   => now(),
                    'status'              => 'registered',
                ])->load('course:id,code,title,units,level');
            });
        });
    }
}
