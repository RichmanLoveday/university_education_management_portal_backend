<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\Course;
use App\Models\AcademicSession;
use App\Models\Semester;
use App\Models\CourseRegistration;
use Illuminate\Database\Seeder;

class CourseRegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $session = AcademicSession::where('is_current', true)->first();
        $semester = Semester::where('is_current', true)->first();

        $students = Student::whereIn('matric_number', ['CSC/2023/001', 'CSC/2023/008'])->get();
        $courses = Course::whereIn('code', ['CSC 301', 'CSC 302'])->get();

        foreach ($students as $student) {
            foreach ($courses as $course) {
                CourseRegistration::create([
                    'student_id'           => $student->id,
                    'course_id'            => $course->id,
                    'academic_session_id'  => $session->id,
                    'semester_id'          => $semester->id,
                    'status'               => 'registered',
                ]);
            }
        }
    }
}
