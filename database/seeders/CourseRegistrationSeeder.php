<?php

namespace Database\Seeders;

use App\Models\AcademicSession;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Student;
use Illuminate\Database\Seeder;

class CourseRegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $current  = AcademicSession::where('is_current', true)->first();
        $previous = AcademicSession::where('name', '2024/2025')->first();

        // Student CSC/2024/015 (200 level) is left unregistered on purpose to demo live registration
        $registrations = [
            'CSC/2023/001' => [[$previous, ['CSC 201', 'CSC 202']], [$current, ['CSC 301', 'CSC 302']]],
            'CSC/2023/008' => [[$previous, ['CSC 201', 'CSC 202']], [$current, ['CSC 301', 'CSC 302']]],
            'SEN/2023/004' => [[$current, ['SEN 301']]],
        ];

        foreach ($registrations as $matric => $periods) {
            $student = Student::where('matric_number', $matric)->first();

            foreach ($periods as [$session, $codes]) {
                foreach (Course::whereIn('code', $codes)->get() as $course) {
                    CourseRegistration::create([
                        'student_id'          => $student->id,
                        'course_id'           => $course->id,
                        'academic_session_id' => $session->id,
                        'semester_id'         => $session->semesters()->where('number', $course->semester)->first()->id,
                        'registration_date'   => $session->start_date,
                        'status'              => 'registered',
                    ]);
                }
            }
        }
    }
}
