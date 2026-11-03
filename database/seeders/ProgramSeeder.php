<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $csc = Department::where('code', 'CSC')->first();
        $sen = Department::where('code', 'SEN')->first();
        $bus = Department::where('code', 'BUS')->first();

        $programs = [
            ['department_id' => $csc->id, 'name' => 'B.Sc Computer Science', 'code' => 'BSC-CSC', 'degree_type' => 'B.Sc', 'duration_years' => 4],
            ['department_id' => $sen->id, 'name' => 'B.Eng Software Engineering', 'code' => 'BENG-SEN', 'degree_type' => 'B.Eng', 'duration_years' => 5],
            ['department_id' => $bus->id, 'name' => 'B.Sc Business Administration', 'code' => 'BSC-BUS', 'degree_type' => 'B.Sc', 'duration_years' => 4],
        ];

        foreach ($programs as $program) {
            Program::create($program);
        }
    }
}
