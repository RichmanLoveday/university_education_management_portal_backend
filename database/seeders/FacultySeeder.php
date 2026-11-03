<?php

namespace Database\Seeders;

use App\Models\Faculty;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        $faculties = [
            ['name' => 'Faculty of Science', 'code' => 'SCI', 'description' => 'Faculty of Science'],
            ['name' => 'Faculty of Engineering', 'code' => 'ENG', 'description' => 'Faculty of Engineering'],
            ['name' => 'Faculty of Management Sciences', 'code' => 'MGT', 'description' => 'Faculty of Management Sciences'],
        ];

        foreach ($faculties as $faculty) {
            Faculty::create($faculty);
        }
    }
}
