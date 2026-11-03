<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'matric_number',
        'department_id',
        'program_id',
        'academic_level',
        'entry_year',
        'current_session_id',
        'status',
    ];

    protected $casts = [
        'academic_level' => 'integer',
        'entry_year'     => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function currentSession()
    {
        return $this->belongsTo(AcademicSession::class, 'current_session_id');
    }

    public function courseRegistrations()
    {
        return $this->hasMany(CourseRegistration::class);
    }

    public function results()
    {
        return $this->hasManyThrough(Result::class, CourseRegistration::class);
    }
}
