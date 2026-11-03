<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lecturer;
use App\Models\AcademicSession;
use App\Models\Semester;
use App\Models\CourseAllocation;
use Illuminate\Database\Seeder;

class CourseAllocationSeeder extends Seeder
{
    public function run(): void
    {
        $lecturer1 = Lecturer::where('staff_id', 'STAFF/CSC/001')->first();
        $session = AcademicSession::where('is_current', true)->first();
        $semester = Semester::where('is_current', true)->first();

        $courses = Course::whereIn('code', ['CSC 301', 'CSC 302'])->get();

        foreach ($courses as $course) {
            CourseAllocation::create([
                'course_id'            => $course->id,
                'lecturer_id'          => $lecturer1->id,
                'academic_session_id'  => $session->id,
                'semester_id'          => $semester->id,
            ]);
        }
    }
}
