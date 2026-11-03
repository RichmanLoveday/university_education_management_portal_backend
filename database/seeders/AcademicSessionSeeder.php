<?php

namespace Database\Seeders;

use App\Models\AcademicSession;
use App\Models\Semester;
use Illuminate\Database\Seeder;

class AcademicSessionSeeder extends Seeder
{
    public function run(): void
    {
        $session = AcademicSession::create([
            'name'       => '2025/2026',
            'start_date' => '2025-09-01',
            'end_date'   => '2026-07-31',
            'is_current' => true,
        ]);

        Semester::create([
            'academic_session_id' => $session->id,
            'name'                => 'First Semester',
            'start_date'          => '2025-09-01',
            'end_date'            => '2026-01-31',
            'is_current'          => true,
        ]);

        Semester::create([
            'academic_session_id' => $session->id,
            'name'                => 'Second Semester',
            'start_date'          => '2026-02-01',
            'end_date'            => '2026-07-31',
            'is_current'          => false,
        ]);
    }
}
