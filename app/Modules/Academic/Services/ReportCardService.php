<?php

namespace App\Modules\Academic\Services;

use App\Modules\Academic\Models\Attendance;
use App\Modules\Academic\Models\Grade;
use App\Modules\Academic\Models\Semester;
use App\Modules\Student\Models\Student;
use Illuminate\Support\Collection;

class ReportCardService
{
    public function build(int $studentId, int $semesterId): array
    {
        $student = Student::with(['classroom', 'department'])->findOrFail($studentId);
        $semester = Semester::with('academicYear')->findOrFail($semesterId);
        $grades = Grade::with('subject')
            ->where('student_id', $studentId)
            ->where('semester_id', $semesterId)
            ->orderBy('subject_id')
            ->get();

        return [
            'student' => $student,
            'semester' => $semester,
            'grades' => $grades,
            'average' => $this->average($grades),
            'attendanceSummary' => $this->attendanceSummary($studentId),
        ];
    }

    protected function average(Collection $grades): float
    {
        if ($grades->isEmpty()) {
            return 0;
        }

        return round((float) $grades->avg('final_result'), 2);
    }

    protected function attendanceSummary(int $studentId): array
    {
        $attendances = Attendance::where('student_id', $studentId)->get();

        return [
            'present' => $attendances->where('status', 'present')->count(),
            'sick' => $attendances->where('status', 'sick')->count(),
            'permission' => $attendances->where('status', 'permission')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
        ];
    }
}
