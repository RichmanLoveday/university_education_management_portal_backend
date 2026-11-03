<?php

namespace Database\Seeders;

use App\Models\CourseAllocation;
use App\Models\Result;
use App\Models\User;
use App\Services\Result\GradingService;
use App\Services\Result\ResultAuditService;
use Illuminate\Database\Seeder;

class ResultSeeder extends Seeder
{
    // Fixed demo scores [ca, exam] per matric number and course
    private const SCORES = [
        'CSC/2023/001' => ['CSC 201' => [26, 52], 'CSC 202' => [24, 45], 'CSC 301' => [27, 48], 'CSC 302' => [22, 41]],
        'CSC/2023/008' => ['CSC 201' => [18, 38], 'CSC 202' => [20, 44], 'CSC 301' => [15, 30], 'CSC 302' => [25, 50]],
    ];

    public function run(): void
    {
        $grading  = app(GradingService::class);
        $audit    = app(ResultAuditService::class);
        $approver = User::where('email', 'deptadmin.cs@uemp.test')->first();

        // Previous session + CSC 301 are approved; CSC 302 is waiting for departmental approval
        $workflow = ['CSC 201' => 'approved', 'CSC 202' => 'approved', 'CSC 301' => 'approved', 'CSC 302' => 'submitted'];

        $allocations = CourseAllocation::with(['course', 'lecturer.user'])->get();

        foreach ($allocations as $allocation) {
            $status = $workflow[$allocation->course->code] ?? null;

            if (!$status) {
                continue;
            }

            $registrations = $allocation->registrationsQuery()->with('student')->get();

            foreach ($registrations as $registration) {
                [$ca, $exam] = self::SCORES[$registration->student->matric_number][$allocation->course->code];

                $result = Result::create([
                    'course_registration_id' => $registration->id,
                    'ca_score'               => $ca,
                    'exam_score'             => $exam,
                    ...$grading->compute($ca, $exam),
                    'status'                 => 'draft',
                    'lecturer_id'            => $allocation->lecturer_id,
                ]);
                $audit->log($result, 'created', $allocation->lecturer->user);

                $old = $audit->snapshot($result);
                $result->update(['status' => 'submitted', 'submitted_at' => now()]);
                $audit->log($result, 'submitted', $allocation->lecturer->user, $old);

                if ($status === 'approved') {
                    $old = $audit->snapshot($result);
                    $result->update(['status' => 'approved', 'approved_by' => $approver->id, 'approved_at' => now()]);
                    $audit->log($result, 'approved', $approver, $old, 'Seeded approval');
                }
            }
        }
    }
}
