<?php

namespace App\Services\Report;

use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Result;
use App\Models\Student;
use App\Services\Result\GpaService;
use Illuminate\Support\Collection;

class ReportService
{
    public function __construct(
        private GpaService $gpaService
    ) {}

    public function resultStatistics(Collection $results): array
    {
        $graded = $results->whereNotNull('total_score');
        $passed = $graded->filter(fn($r) => (float) $r->grade_point > 0);

        return [
            'total_results'      => $results->count(),
            'status_breakdown'   => $results->countBy('status'),
            'grade_distribution' => $graded->countBy('grade')->sortKeys(),
            'average_score'      => $graded->isNotEmpty() ? round($graded->avg(fn($r) => (float) $r->total_score), 2) : null,
            'highest_score'      => $graded->isNotEmpty() ? (float) $graded->max(fn($r) => (float) $r->total_score) : null,
            'lowest_score'       => $graded->isNotEmpty() ? (float) $graded->min(fn($r) => (float) $r->total_score) : null,
            'pass_count'         => $passed->count(),
            'fail_count'         => $graded->count() - $passed->count(),
            'pass_rate'          => $graded->isNotEmpty() ? round($passed->count() / $graded->count() * 100, 2) : null,
        ];
    }

    public function courseRegistrationReport(?int $departmentId, ?int $sessionId, ?int $semesterId): array
    {
        $courses = Course::query()
            ->when($departmentId, fn($q) => $q->where('department_id', $departmentId))
            ->with('department:id,name,code')
            ->withCount(['courseRegistrations as registered_students' => fn($q) => $q
                ->where('status', 'registered')
                ->when($sessionId, fn($q) => $q->where('academic_session_id', $sessionId))
                ->when($semesterId, fn($q) => $q->where('semester_id', $semesterId))])
            ->orderBy('level')
            ->orderBy('code')
            ->get(['id', 'department_id', 'code', 'title', 'units', 'level', 'semester']);

        $registrations = CourseRegistration::query()
            ->where('status', 'registered')
            ->when($departmentId, fn($q) => $q->whereHas('student', fn($q) => $q->where('department_id', $departmentId)))
            ->when($sessionId, fn($q) => $q->where('academic_session_id', $sessionId))
            ->when($semesterId, fn($q) => $q->where('semester_id', $semesterId));

        return [
            'summary' => [
                'total_registrations'   => (clone $registrations)->count(),
                'students_registered'   => (clone $registrations)->distinct('student_id')->count('student_id'),
                'courses_with_students' => $courses->where('registered_students', '>', 0)->count(),
            ],
            'courses' => $courses,
        ];
    }

    // Based on APPROVED results only
    public function academicPerformanceReport(?int $departmentId, ?int $sessionId, ?int $semesterId): array
    {
        $results = Result::query()
            ->where('status', 'approved')
            ->whereHas('courseRegistration', fn($q) => $q
                ->when($sessionId, fn($q) => $q->where('academic_session_id', $sessionId))
                ->when($semesterId, fn($q) => $q->where('semester_id', $semesterId))
                ->when($departmentId, fn($q) => $q->whereHas('course', fn($q) => $q->where('department_id', $departmentId))))
            ->with([
                'courseRegistration:id,student_id,course_id,academic_session_id,semester_id',
                'courseRegistration.course:id,code,title,units,level',
                'courseRegistration.student:id,user_id,matric_number,academic_level',
                'courseRegistration.student.user:id,name',
            ])
            ->get();

        $courses = $results
            ->groupBy('courseRegistration.course_id')
            ->map(fn(Collection $group) => [
                'course'     => $group->first()->courseRegistration->course->only(['id', 'code', 'title', 'units', 'level']),
                'statistics' => $this->resultStatistics($group),
            ])
            ->sortBy('course.code')
            ->values();

        $students = $results
            ->groupBy('courseRegistration.student_id')
            ->map(function (Collection $group) {
                $student = $group->first()->courseRegistration->student;

                return [
                    'student_id'     => $student->id,
                    'name'           => $student->user?->name,
                    'matric_number'  => $student->matric_number,
                    'academic_level' => $student->academic_level,
                    ...$this->gpaService->calculate($group),
                ];
            })
            ->sortByDesc('gpa')
            ->values();

        return [
            'overall'  => $this->resultStatistics($results),
            'courses'  => $courses,
            'students' => $students,
        ];
    }

    public function studentReport(?int $departmentId): array
    {
        $students = Student::query()->when($departmentId, fn($q) => $q->where('students.department_id', $departmentId));

        return [
            'total'      => (clone $students)->count(),
            'by_level'   => (clone $students)->selectRaw('academic_level, count(*) as total')->groupBy('academic_level')->orderBy('academic_level')->pluck('total', 'academic_level'),
            'by_status'  => (clone $students)->selectRaw('students.status, count(*) as total')->groupBy('students.status')->pluck('total', 'status'),
            'by_program' => (clone $students)->join('programs', 'programs.id', '=', 'students.program_id')
                ->selectRaw('programs.name as program, count(*) as total')
                ->groupBy('programs.name')
                ->pluck('total', 'program'),
        ];
    }
}
