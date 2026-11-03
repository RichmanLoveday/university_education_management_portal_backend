<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Department;
use App\Models\Program;
use App\Models\AcademicSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $cscDept = Department::where('code', 'CSC')->first();
        $senDept = Department::where('code', 'SEN')->first();
        $cscProgram = Program::where('code', 'BSC-CSC')->first();
        $currentSession = AcademicSession::where('is_current', true)->first();

        // 1. Super Admin
        User::create([
            'name'     => 'Super Admin',
            'email'    => 'superadmin@uemp.test',
            'password' => Hash::make('password'),
            'role'     => 'super_admin',
            'is_active'=> true,
        ]);

        // 2. Department Admin (Computer Science)
        $deptAdmin = User::create([
            'name'     => 'Dr. Adebayo Okonkwo',
            'email'    => 'deptadmin.cs@uemp.test',
            'password' => Hash::make('password'),
            'role'     => 'department_admin',
            'is_active'=> true,
        ]);

        // 3. Lecturers
        $lecturer1User = User::create([
            'name'     => 'Dr. Chinedu Eze',
            'email'    => 'lecturer1@uemp.test',
            'password' => Hash::make('password'),
            'role'     => 'lecturer',
            'is_active'=> true,
        ]);

        Lecturer::create([
            'user_id'       => $lecturer1User->id,
            'staff_id'      => 'STAFF/CSC/001',
            'department_id' => $cscDept->id,
            'title'         => 'Dr',
            'rank'          => 'Senior Lecturer',
            'is_active'     => true,
        ]);

        $lecturer2User = User::create([
            'name'     => 'Mrs. Ngozi Adeyemi',
            'email'    => 'lecturer2@uemp.test',
            'password' => Hash::make('password'),
            'role'     => 'lecturer',
            'is_active'=> true,
        ]);

        Lecturer::create([
            'user_id'       => $lecturer2User->id,
            'staff_id'      => 'STAFF/CSC/002',
            'department_id' => $cscDept->id,
            'title'         => 'Mrs',
            'rank'          => 'Lecturer I',
            'is_active'     => true,
        ]);

        // 4. Students
        $student1User = User::create([
            'name'     => 'Emmanuel Okoro',
            'email'    => 'student1@uemp.test',
            'password' => Hash::make('password'),
            'role'     => 'student',
            'is_active'=> true,
        ]);

        Student::create([
            'user_id'            => $student1User->id,
            'matric_number'     => 'CSC/2023/001',
            'department_id'      => $cscDept->id,
            'program_id'         => $cscProgram->id,
            'academic_level'     => 300,
            'entry_year'         => 2023,
            'current_session_id' => $currentSession->id,
            'status'             => 'active',
        ]);

        $student2User = User::create([
            'name'     => 'Fatima Bello',
            'email'    => 'student2@uemp.test',
            'password' => Hash::make('password'),
            'role'     => 'student',
            'is_active'=> true,
        ]);

        Student::create([
            'user_id'            => $student2User->id,
            'matric_number'     => 'CSC/2024/015',
            'department_id'      => $cscDept->id,
            'program_id'         => $cscProgram->id,
            'academic_level'     => 200,
            'entry_year'         => 2024,
            'current_session_id' => $currentSession->id,
            'status'             => 'active',
        ]);

        $student3User = User::create([
            'name'     => 'Ibrahim Musa',
            'email'    => 'student3@uemp.test',
            'password' => Hash::make('password'),
            'role'     => 'student',
            'is_active'=> true,
        ]);

        Student::create([
            'user_id'            => $student3User->id,
            'matric_number'     => 'CSC/2023/008',
            'department_id'      => $cscDept->id,
            'program_id'         => $cscProgram->id,
            'academic_level'     => 300,
            'entry_year'         => 2023,
            'current_session_id' => $currentSession->id,
            'status'             => 'active',
        ]);
    }
}
