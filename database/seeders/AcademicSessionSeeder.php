<?php

namespace Database\Seeders;

use App\Models\AcademicSession;
use App\Models\Semester;
use Illuminate\Database\Seeder;

class AcademicSessionSeeder extends Seeder
{
    public function run(): void
    {
        // Previous session gives students an academic history for CGPA
        $previous = AcademicSession::create([
            'name'       => '2024/2025',
            'start_date' => '2024-09-01',
            'end_date'   => '2025-07-31',
            'is_current' => false,
        ]);

        Semester::create([
            'academic_session_id' => $previous->id,
            'name'                => 'First Semester',
            'number'              => 1,
            'start_date'          => '2024-09-01',
            'end_date'            => '2025-01-31',
            'is_current'          => false,
        ]);

        Semester::create([
            'academic_session_id' => $previous->id,
            'name'                => 'Second Semester',
            'number'              => 2,
            'start_date'          => '2025-02-01',
            'end_date'            => '2025-07-31',
            'is_current'          => false,
        ]);

        // Current session
        $session = AcademicSession::create([
            'name'       => '2025/2026',
            'start_date' => '2025-09-01',
            'end_date'   => '2026-07-31',
            'is_current' => true,
        ]);

        Semester::create([
            'academic_session_id' => $session->id,
            'name'                => 'First Semester',
            'number'              => 1,
            'start_date'          => '2025-09-01',
            'end_date'            => '2026-01-31',
            'is_current'          => true,
        ]);

        Semester::create([
            'academic_session_id' => $session->id,
            'name'                => 'Second Semester',
            'number'              => 2,
            'start_date'          => '2026-02-01',
            'end_date'            => '2026-07-31',
            'is_current'          => false,
        ]);
    }
}
