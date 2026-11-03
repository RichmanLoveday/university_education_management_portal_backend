<?php

namespace Database\Seeders;

use App\Models\GradingScale;
use Illuminate\Database\Seeder;

class GradingScaleSeeder extends Seeder
{
    public function run(): void
    {
        $scales = [
            ['min_score' => 70, 'max_score' => 100, 'grade' => 'A', 'grade_point' => 5.00],
            ['min_score' => 60, 'max_score' => 69.99, 'grade' => 'B', 'grade_point' => 4.00],
            ['min_score' => 50, 'max_score' => 59.99, 'grade' => 'C', 'grade_point' => 3.00],
            ['min_score' => 45, 'max_score' => 49.99, 'grade' => 'D', 'grade_point' => 2.00],
            ['min_score' => 40, 'max_score' => 44.99, 'grade' => 'E', 'grade_point' => 1.00],
            ['min_score' => 0,  'max_score' => 39.99, 'grade' => 'F', 'grade_point' => 0.00],
        ];

        foreach ($scales as $scale) {
            GradingScale::create($scale);
        }
    }
}
