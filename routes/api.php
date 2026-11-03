<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\SuperAdmin\FacultyController;
use App\Http\Controllers\SuperAdmin\DepartmentController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\AcademicSessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {

    // Public routes
    Route::post('/login', [AuthController::class, 'login']);

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
    });
});



Route::middleware('auth:sanctum')->group(function () {
    // SUPPER ADMIN ROUTES
    Route::middleware('role:super_admin')->prefix('super-admin')->name('super-admin.')->group(function () {
        // Dashboard

        // Faculty Management
        Route::apiResource('faculties', FacultyController::class);

        // Department Management
        Route::apiResource('departments', DepartmentController::class);

        // Programs
        // Route::apiResource('programs', \App\Http\Controllers\SuperAdmin\ProgramController::class);


        // Academic Sessions & Semesters
        Route::apiResource('academic-sessions', AcademicSessionController::class);
        Route::post('academic-sessions/{academic_session}/set-current', [AcademicSessionController::class, 'setCurrent']);

        // Semesters
        Route::apiResource('semesters', \App\Http\Controllers\SuperAdmin\SemesterController::class);
        Route::post('semesters/{semester}/set-current', [\App\Http\Controllers\SuperAdmin\SemesterController::class, 'setCurrent']);


        // Route::apiResource('students', \App\Http\Controllers\SuperAdmin\StudentController::class);
        // Route::apiResource('lecturers', \App\Http\Controllers\SuperAdmin\LecturerController::class);

        // // Courses
        // Route::apiResource('courses', \App\Http\Controllers\SuperAdmin\CourseController::class);

        // // Reports
        // Route::get('/reports/overview', [\App\Http\Controllers\SuperAdmin\ReportController::class, 'overview']);


        // User Management
        Route::apiResource('users', UserController::class);
        Route::patch('users/{user}/status', [UserController::class, 'updateStatus']);
        Route::patch('users/{user}/role', [UserController::class, 'updateRole']);

    });
});
