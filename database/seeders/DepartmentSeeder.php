<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $science = Faculty::where('code', 'SCI')->first();
        $engineering = Faculty::where('code', 'ENG')->first();
        $management = Faculty::where('code', 'MGT')->first();

        $departments = [
            ['faculty_id' => $science->id, 'name' => 'Computer Science', 'code' => 'CSC'],
            ['faculty_id' => $science->id, 'name' => 'Mathematics', 'code' => 'MTH'],
            ['faculty_id' => $engineering->id, 'name' => 'Software Engineering', 'code' => 'SEN'],
            ['faculty_id' => $management->id, 'name' => 'Business Administration', 'code' => 'BUS'],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
