<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'is_active'         => 'boolean',
        'password'          => 'hashed',
    ];

    // Relationships
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function lecturer()
    {
        return $this->hasOne(Lecturer::class);
    }

    public function approvedResults()
    {
        return $this->hasMany(Result::class, 'approved_by');
    }

    public function resultAudits()
    {
        return $this->hasMany(ResultAudit::class, 'performed_by');
    }

    public function departmentAdmin()
    {
        return $this->hasOne(DepartmentAdmin::class);
    }

    // Helper to easily get the department_id of a Department Admin
    public function getDepartmentIdAttribute()
    {
        return $this->departmentAdmin?->department_id;
    }

    public function getDepartmentId(): ?int
    {
        return $this->departmentAdmin?->department_id;
    }

    // Helpers
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isDepartmentAdmin(): bool
    {
        return $this->role === 'department_admin';
    }

    public function isLecturer(): bool
    {
        return $this->role === 'lecturer';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }
}
