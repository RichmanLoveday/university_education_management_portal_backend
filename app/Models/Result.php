<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Result extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_registration_id',
        'ca_score',
        'exam_score',
        'total_score',
        'grade',
        'grade_point',
        'status',
        'lecturer_id',
        'submitted_at',
        'approved_by',
        'approved_at',
        'rejection_reason',
    ];

    protected $casts = [
        'ca_score'     => 'decimal:2',
        'exam_score'   => 'decimal:2',
        'total_score'  => 'decimal:2',
        'grade_point'  => 'decimal:2',
        'submitted_at' => 'datetime',
        'approved_at'  => 'datetime',
    ];

    public function courseRegistration()
    {
        return $this->belongsTo(CourseRegistration::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function audits()
    {
        return $this->hasMany(ResultAudit::class);
    }
}
