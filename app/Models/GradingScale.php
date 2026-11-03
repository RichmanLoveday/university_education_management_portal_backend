<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradingScale extends Model
{
    use HasFactory;

    protected $fillable = [
        'min_score',
        'max_score',
        'grade',
        'grade_point',
        'is_active',
    ];

    protected $casts = [
        'min_score'   => 'decimal:2',
        'max_score'   => 'decimal:2',
        'grade_point' => 'decimal:2',
        'is_active'   => 'boolean',
    ];
}
