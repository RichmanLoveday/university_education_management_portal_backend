<?php

namespace App\Services\Result;

use App\Models\Result;
use App\Models\Student;
use Illuminate\Support\Collection;

class GpaService
{
    // GPA = sum(units x grade_point) / sum(units)
    public function calculate(Collection $results): array
    {
        $totalUnits  = 0;
        $totalPoints = 0.0;

        foreach ($results as $result) {
            $units = (int) $result->courseRegistration->course->units;
            $totalUnits  += $units;
            $totalPoints += $units * (float) $result->grade_point;
        }

        return [
            'total_units'         => $totalUnits,
            'total_grade_points'  => round($totalPoints, 2),
            'gpa'                 => $totalUnits > 0 ? round($totalPoints / $totalUnits, 2) : 0.0,
        ];
    }

    public function academicHistory(Student $student, ?int $sessionId = null, ?int $semesterId = null): array
    {
        // Only APPROVED results are ever shown to students or counted in CGPA
        $results = Result::query()
            ->where('status', 'approved')
            ->whereHas('courseRegistration', fn($q) => $q->where('student_id', $student->id))
            ->with([
                'courseRegistration.course:id,code,title,units,level',
                'courseRegistration.academicSession:id,name',
                'courseRegistration.semester:id,name,number',
            ])
            ->get()
            ->sortBy([
                fn($a, $b) => $a->courseRegistration->academic_session_id <=> $b->courseRegistration->academic_session_id,
                fn($a, $b) => $a->courseRegistration->semester->number <=> $b->courseRegistration->semester->number,
            ]);

        $semesters = $results
            ->groupBy(fn($r) => $r->courseRegistration->academic_session_id . '-' . $r->courseRegistration->semester_id)
            ->map(function (Collection $group) {
                $registration = $group->first()->courseRegistration;

                return [
                    'academic_session' => $registration->academicSession?->only(['id', 'name']),
                    'semester'         => $registration->semester?->only(['id', 'name', 'number']),
                    'results'          => $group->map(fn($r) => $this->formatResult($r))->values(),
                    'summary'          => $this->calculate($group),
                ];
            })
            ->when($sessionId, fn($c) => $c->filter(fn($s) => $s['academic_session']['id'] == $sessionId))
            ->when($semesterId, fn($c) => $c->filter(fn($s) => $s['semester']['id'] == $semesterId))
            ->values();

        $cumulative = $this->calculate($results);

        return [
            'semesters' => $semesters,
            'cumulative' => [
                'total_units'        => $cumulative['total_units'],
                'total_grade_points' => $cumulative['total_grade_points'],
                'cgpa'               => $cumulative['gpa'],
                'class_of_degree'    => $this->classOfDegree($cumulative['gpa'], $cumulative['total_units']),
            ],
        ];
    }

    public function cgpa(Student $student): float
    {
        return $this->academicHistory($student)['cumulative']['cgpa'];
    }

    // Class of degree on a 5-point scale
    public function classOfDegree(float $cgpa, int $units = 1): ?string
    {
        if ($units === 0) {
            return null;
        }

        return match (true) {
            $cgpa >= 4.50 => 'First Class',
            $cgpa >= 3.50 => 'Second Class Upper',
            $cgpa >= 2.40 => 'Second Class Lower',
            $cgpa >= 1.50 => 'Third Class',
            $cgpa >= 1.00 => 'Pass',
            default       => 'Fail',
        };
    }

    private function formatResult(Result $result): array
    {
        $course = $result->courseRegistration->course;

        return [
            'result_id'   => $result->id,
            'course_code' => $course->code,
            'course_title'=> $course->title,
            'units'       => $course->units,
            'ca_score'    => $result->ca_score,
            'exam_score'  => $result->exam_score,
            'total_score' => $result->total_score,
            'grade'       => $result->grade,
            'grade_point' => $result->grade_point,
        ];
    }
}
