<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DepartmentAdmin;
use App\Http\Controllers\Lecturer;
use App\Http\Controllers\Student;
use App\Http\Controllers\SuperAdmin;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    // Public routes (rate limited against brute force)
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    // SUPER ADMIN ROUTES
    Route::middleware('role:super_admin')->prefix('super-admin')->name('super-admin.')->group(function () {
        Route::get('dashboard', [SuperAdmin\DashboardController::class, 'index']);

        // Faculty Management
        Route::apiResource('faculties', SuperAdmin\FacultyController::class);

        // Department Management
        Route::apiResource('departments', SuperAdmin\DepartmentController::class);
        Route::apiResource('department-admins', SuperAdmin\DepartmentAdminController::class);

        // Programs
        Route::apiResource('programs', SuperAdmin\ProgramController::class);

        // Academic Sessions & Semesters
        Route::apiResource('academic-sessions', SuperAdmin\AcademicSessionController::class);
        Route::post('academic-sessions/{academic_session}/set-current', [SuperAdmin\AcademicSessionController::class, 'setCurrent']);

        Route::apiResource('semesters', SuperAdmin\SemesterController::class);
        Route::post('semesters/{semester}/set-current', [SuperAdmin\SemesterController::class, 'setCurrent']);

        // Students and lecturer management
        Route::apiResource('students', SuperAdmin\StudentController::class);
        Route::apiResource('lecturers', SuperAdmin\LecturerController::class);

        // Courses & allocations
        Route::apiResource('courses', SuperAdmin\CourseController::class);
        Route::apiResource('course-allocations', SuperAdmin\CourseAllocationController::class);

        // Grading scale (configurable)
        Route::apiResource('grading-scales', SuperAdmin\GradingScaleController::class);

        // Monitoring (read-only)
        Route::get('course-registrations', [SuperAdmin\OversightController::class, 'courseRegistrations']);
        Route::get('results', [SuperAdmin\OversightController::class, 'results']);
        Route::get('results/{result}', [SuperAdmin\OversightController::class, 'showResult']);
        Route::get('audit-logs', [SuperAdmin\OversightController::class, 'auditLogs']);

        // Reports
        Route::prefix('reports')->group(function () {
            Route::get('students', [SuperAdmin\ReportController::class, 'students']);
            Route::get('course-registrations', [SuperAdmin\ReportController::class, 'courseRegistrations']);
            Route::get('academic-performance', [SuperAdmin\ReportController::class, 'academicPerformance']);
        });

        // User Management
        Route::apiResource('users', SuperAdmin\UserController::class);
        Route::patch('users/{user}/status', [SuperAdmin\UserController::class, 'updateStatus']);
        Route::patch('users/{user}/role', [SuperAdmin\UserController::class, 'updateRole']);
    });

    // DEPARTMENT ADMIN ROUTES (every record is scoped to the admin's department)
    Route::middleware('role:department_admin')->prefix('department-admin')->name('department-admin.')->group(function () {
        Route::get('dashboard', [DepartmentAdmin\DashboardController::class, 'index']);

        Route::apiResource('students', DepartmentAdmin\StudentController::class);
        Route::apiResource('lecturers', DepartmentAdmin\LecturerController::class);
        Route::apiResource('programs', DepartmentAdmin\ProgramController::class);
        Route::apiResource('courses', DepartmentAdmin\CourseController::class);
        Route::apiResource('course-allocations', DepartmentAdmin\CourseAllocationController::class);
        Route::get('course-registrations', [DepartmentAdmin\CourseRegistrationController::class, 'index']);

        // Result review & approval
        Route::prefix('results')->group(function () {
            Route::get('submissions', [DepartmentAdmin\ResultController::class, 'submissions']);
            Route::get('courses/{course_allocation}', [DepartmentAdmin\ResultController::class, 'course']);
            Route::post('courses/{course_allocation}/approve', [DepartmentAdmin\ResultController::class, 'approve']);
            Route::post('courses/{course_allocation}/reject', [DepartmentAdmin\ResultController::class, 'reject']);
            Route::post('courses/{course_allocation}/reopen', [DepartmentAdmin\ResultController::class, 'reopen']);
            Route::get('{result}', [DepartmentAdmin\ResultController::class, 'show']);
        });

        Route::prefix('reports')->group(function () {
            Route::get('students', [DepartmentAdmin\ReportController::class, 'students']);
            Route::get('course-registrations', [DepartmentAdmin\ReportController::class, 'courseRegistrations']);
            Route::get('academic-performance', [DepartmentAdmin\ReportController::class, 'academicPerformance']);
        });
    });

    // LECTURER ROUTES (only courses allocated to the lecturer)
    Route::middleware('role:lecturer')->prefix('lecturer')->name('lecturer.')->group(function () {
        Route::get('dashboard', [Lecturer\DashboardController::class, 'index']);

        Route::get('courses', [Lecturer\CourseController::class, 'index']);
        Route::get('courses/{course_allocation}', [Lecturer\CourseController::class, 'show']);
        Route::get('courses/{course_allocation}/summary', [Lecturer\CourseController::class, 'summary']);

        Route::get('courses/{course_allocation}/results', [Lecturer\ResultController::class, 'index']);
        Route::post('courses/{course_allocation}/results', [Lecturer\ResultController::class, 'store']);
        Route::post('courses/{course_allocation}/results/submit', [Lecturer\ResultController::class, 'submit']);
        Route::get('results/{result}', [Lecturer\ResultController::class, 'show']);
    });

    // STUDENT ROUTES (always resolved from the authenticated student)
    Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {
        Route::get('dashboard', [Student\DashboardController::class, 'index']);

        Route::get('profile', [Student\ProfileController::class, 'show']);
        Route::patch('profile', [Student\ProfileController::class, 'update']);

        Route::get('courses/available', [Student\CourseRegistrationController::class, 'available']);
        Route::get('course-registrations', [Student\CourseRegistrationController::class, 'index']);
        Route::post('course-registrations', [Student\CourseRegistrationController::class, 'store']);
        Route::get('course-registrations/slip', [Student\CourseRegistrationController::class, 'slip']);
        Route::delete('course-registrations/{course_registration}', [Student\CourseRegistrationController::class, 'destroy']);

        Route::get('results', [Student\ResultController::class, 'index']);
        Route::get('results/statement', [Student\ResultController::class, 'statement']);
    });
});
