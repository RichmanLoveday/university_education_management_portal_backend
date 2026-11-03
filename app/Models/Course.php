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
}
