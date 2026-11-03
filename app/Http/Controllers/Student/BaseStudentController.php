<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

// A student only ever works with their own record, so it is resolved from the token
abstract class BaseStudentController extends Controller
{
    protected function student(Request $request): Student
    {
        $student = $request->user()->student;

        abort_if(!$student, 403, 'No student profile is linked to this account.');

        return $student;
    }
}
