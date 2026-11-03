<?php

namespace Database\Seeders;

use App\Models\AcademicSession;
use App\Models\Course;
use App\Models\CourseAllocation;
use App\Models\Lecturer;
use Illuminate\Database\Seeder;

class CourseAllocationSeeder extends Seeder
{
    public function run(): void
    {
        $lecturer1   = Lecturer::where('staff_id', 'STAFF/CSC/001')->first();
        $lecturer2   = Lecturer::where('staff_id', 'STAFF/CSC/002')->first();
        $senLecturer = Lecturer::where('staff_id', 'STAFF/SEN/001')->first();

        $current  = AcademicSession::where('is_current', true)->first();
        $previous = AcademicSession::where('name', '2024/2025')->first();

        $allocations = [
            [$previous, 'CSC 201', $lecturer2],
            [$previous, 'CSC 202', $lecturer2],
            [$current,  'CSC 301', $lecturer1],
            [$current,  'CSC 302', $lecturer1],
            [$current,  'CSC 305', $lecturer2],
            [$current,  'CSC 201', $lecturer2],
            [$current,  'SEN 301', $senLecturer],
        ];

        foreach ($allocations as [$session, $code, $lecturer]) {
            $course   = Course::where('code', $code)->first();
            $semester = $session->semesters()->where('number', $course->semester)->first();

            CourseAllocation::create([
                'course_id'           => $course->id,
                'lecturer_id'         => $lecturer->id,
                'academic_session_id' => $session->id,
                'semester_id'         => $semester->id,
            ]);
        }
    }
}
