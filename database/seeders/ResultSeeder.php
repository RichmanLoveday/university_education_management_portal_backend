<?php

namespace Database\Seeders;

use App\Models\CourseRegistration;
use App\Models\Result;
use App\Models\Lecturer;
use Illuminate\Database\Seeder;

class ResultSeeder extends Seeder
{
    public function run(): void
    {
        $lecturer = Lecturer::where('staff_id', 'STAFF/CSC/001')->first();
        $registrations = CourseRegistration::with('course')->get();

        foreach ($registrations as $index => $registration) {
            $ca = rand(15, 30);
            $exam = rand(30, 60);
            $total = $ca + $exam;

            $grade = match(true) {
                $total >= 70 => 'A',
                $total >= 60 => 'B',
                $total >= 50 => 'C',
                $total >= 45 => 'D',
                $total >= 40 => 'E',
                default => 'F',
            };

            $gradePoint = match($grade) {
                'A' => 5.00,
                'B' => 4.00,
                'C' => 3.00,
                'D' => 2.00,
                'E' => 1.00,
                default => 0.00,
            };

            // Vary the status for demo purposes
            $status = match($index % 4) {
                0 => 'approved',
                1 => 'submitted',
                2 => 'draft',
                3 => 'rejected',
            };

            Result::create([
                'course_registration_id' => $registration->id,
                'ca_score'               => $ca,
                'exam_score'             => $exam,
                'total_score'            => $total,
                'grade'                  => $grade,
                'grade_point'            => $gradePoint,
                'status'                 => $status,
                'lecturer_id'            => $lecturer->id,
                'submitted_at'           => in_array($status, ['submitted', 'approved', 'rejected']) ? now() : null,
                'rejection_reason'       => $status === 'rejected' ? 'Incomplete continuous assessment records' : null,
            ]);
        }
    }
}
