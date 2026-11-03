<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'department_id',
        'code',
        'title',
        'units',
        'level',
        'semester',
        'description',
        'is_active',
    ];

    protected $casts = [
        'units'     => 'integer',
        'level'     => 'integer',
        'semester'  => 'integer',
        'is_active' => 'boolean',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function courseAllocations()
    {
        return $this->hasMany(CourseAllocation::class);
    }

    public function courseRegistrations()
    {
        return $this->hasMany(CourseRegistration::class);
    }

    // Courses a student can register: own department, level, semester and active
    public function scopeAvailableFor($query, Student $student, int $semesterNumber)
    {
        return $query
            ->where('department_id', $student->department_id)
            ->where('level', $student->academic_level)
            ->where('semester', $semesterNumber)
            ->where('is_active', true);
    }
}
