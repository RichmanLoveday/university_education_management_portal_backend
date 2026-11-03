<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'lecturer_id',
        'academic_session_id',
        'semester_id',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function academicSession()
    {
        return $this->belongsTo(AcademicSession::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    // Active registrations for this course in the same session & semester
    public function registrationsQuery()
    {
        return CourseRegistration::query()
            ->where('course_id', $this->course_id)
            ->where('academic_session_id', $this->academic_session_id)
            ->where('semester_id', $this->semester_id)
            ->where('status', 'registered');
    }

    // Results that belong to this allocation
    public function resultsQuery()
    {
        return Result::query()->whereHas('courseRegistration', fn($q) => $q
            ->where('course_id', $this->course_id)
            ->where('academic_session_id', $this->academic_session_id)
            ->where('semester_id', $this->semester_id));
    }
}
