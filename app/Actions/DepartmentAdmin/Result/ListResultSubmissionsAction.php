<?php

namespace App\Actions\DepartmentAdmin\Result;

use App\Models\CourseAllocation;
use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ListResultSubmissionsAction
{
    public function execute(Request $request, int $departmentId): Collection
    {
        // Defaults to courses with results awaiting review
        $status = $request->input('status', 'submitted');

        $allocations = CourseAllocation::query()
            ->whereHas('course', fn($q) => $q->where('department_id', $departmentId))
            ->with([
                'course:id,code,title,units,level',
                'lecturer:id,user_id,staff_id',
                'lecturer.user:id,name',
                'academicSession:id,name',
                'semester:id,name',
            ])
            ->when($request->academic_session_id, fn($q) => $q->where('academic_session_id', $request->academic_session_id))
            ->when($request->semester_id, fn($q) => $q->where('semester_id', $request->semester_id))
            ->get();

        // One grouped query for all status counts instead of N queries
        $counts = Result::query()
            ->join('course_registrations', 'course_registrations.id', '=', 'results.course_registration_id')
            ->whereIn('course_registrations.course_id', $allocations->pluck('course_id'))
            ->selectRaw('course_registrations.course_id, course_registrations.academic_session_id, course_registrations.semester_id, results.status, count(*) as total')
            ->groupBy('course_registrations.course_id', 'course_registrations.academic_session_id', 'course_registrations.semester_id', 'results.status')
            ->get()
            ->groupBy(fn($row) => "{$row->course_id}-{$row->academic_session_id}-{$row->semester_id}");

        return $allocations
            ->map(function (CourseAllocation $allocation) use ($counts) {
                $key = "{$allocation->course_id}-{$allocation->academic_session_id}-{$allocation->semester_id}";
                $byStatus = collect(['draft' => 0, 'submitted' => 0, 'approved' => 0, 'rejected' => 0])
                    ->merge(($counts[$key] ?? collect())->pluck('total', 'status')->map(fn($v) => (int) $v));

                return [
                    'course_allocation_id' => $allocation->id,
                    'course'               => $allocation->course,
                    'lecturer'             => $allocation->lecturer,
                    'academic_session'     => $allocation->academicSession,
                    'semester'             => $allocation->semester,
                    'result_counts'        => $byStatus,
                    'last_submitted_at'    => $allocation->resultsQuery()->max('submitted_at'),
                ];
            })
            ->when($status !== 'all', fn($c) => $c->filter(fn($row) => $row['result_counts'][$status] > 0))
            ->sortByDesc('last_submitted_at')
            ->values();
    }
}
