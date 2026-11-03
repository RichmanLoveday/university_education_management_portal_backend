<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\CourseAllocation;
use App\Models\Lecturer;
use Illuminate\Http\Request;

// Lecturers can only work on courses allocated to them
abstract class BaseLecturerController extends Controller
{
    protected function lecturer(Request $request): Lecturer
    {
        $lecturer = $request->user()->lecturer;

        abort_if(!$lecturer, 403, 'No lecturer profile is linked to this account.');
        abort_if(!$lecturer->is_active, 403, 'Your lecturer profile is inactive. Please contact your department.');

        return $lecturer;
    }

    protected function ensureAssigned(Request $request, CourseAllocation $allocation): void
    {
        abort_if(
            $allocation->lecturer_id !== $this->lecturer($request)->id,
            403,
            'You can only manage courses assigned to you.'
        );
    }
}
