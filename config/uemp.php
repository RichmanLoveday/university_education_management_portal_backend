<?php

return [
    // CA + Exam must add up to 100
    'max_ca_score'   => env('UEMP_MAX_CA_SCORE', 30),
    'max_exam_score' => env('UEMP_MAX_EXAM_SCORE', 70),

    // Credit unit limits per student per semester
    'min_registration_units' => env('UEMP_MIN_REGISTRATION_UNITS', 0),
    'max_registration_units' => env('UEMP_MAX_REGISTRATION_UNITS', 24),

    // Fallback only; the live scale is managed by the Super Admin
    'default_grading_scale' => [
        ['min_score' => 70, 'max_score' => 100,   'grade' => 'A', 'grade_point' => 5.00],
        ['min_score' => 60, 'max_score' => 69.99, 'grade' => 'B', 'grade_point' => 4.00],
        ['min_score' => 50, 'max_score' => 59.99, 'grade' => 'C', 'grade_point' => 3.00],
        ['min_score' => 45, 'max_score' => 49.99, 'grade' => 'D', 'grade_point' => 2.00],
        ['min_score' => 40, 'max_score' => 44.99, 'grade' => 'E', 'grade_point' => 1.00],
        ['min_score' => 0,  'max_score' => 39.99, 'grade' => 'F', 'grade_point' => 0.00],
    ],
];
