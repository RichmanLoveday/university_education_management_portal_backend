<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Department;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $csc = Department::where('code', 'CSC')->first();
        $sen = Department::where('code', 'SEN')->first();

        $courses = [
            ['department_id' => $csc->id, 'code' => 'CSC 201', 'title' => 'Introduction to Programming', 'units' => 3, 'level' => 200, 'semester' => 1],
            ['department_id' => $csc->id, 'code' => 'CSC 202', 'title' => 'Object Oriented Programming', 'units' => 3, 'level' => 200, 'semester' => 2],
            ['department_id' => $csc->id, 'code' => 'CSC 301', 'title' => 'Data Structures and Algorithms', 'units' => 3, 'level' => 300, 'semester' => 1],
            ['department_id' => $csc->id, 'code' => 'CSC 302', 'title' => 'Database Management Systems', 'units' => 3, 'level' => 300, 'semester' => 1],
            ['department_id' => $csc->id, 'code' => 'CSC 303', 'title' => 'Operating Systems', 'units' => 3, 'level' => 300, 'semester' => 2],
            ['department_id' => $csc->id, 'code' => 'CSC 304', 'title' => 'Software Engineering', 'units' => 3, 'level' => 300, 'semester' => 2],
            ['department_id' => $csc->id, 'code' => 'CSC 305', 'title' => 'Computer Architecture', 'units' => 2, 'level' => 300, 'semester' => 1],
            ['department_id' => $sen->id, 'code' => 'SEN 301', 'title' => 'Software Requirements Engineering', 'units' => 3, 'level' => 300, 'semester' => 1],
        ];

        foreach ($courses as $course) {
            Course::create($course);
        }
    }
}
