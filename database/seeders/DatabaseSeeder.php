<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            GradingScaleSeeder::class,
            FacultySeeder::class,
            DepartmentSeeder::class,
            ProgramSeeder::class,
            AcademicSessionSeeder::class,
            UserSeeder::class,
            CourseSeeder::class,
            CourseAllocationSeeder::class,
            CourseRegistrationSeeder::class,
            ResultSeeder::class,
        ]);
    }
}