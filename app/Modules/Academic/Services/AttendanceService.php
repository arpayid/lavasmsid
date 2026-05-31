<?php

namespace App\Modules\Academic\Services;

use App\Modules\Academic\Models\Attendance;
use App\Modules\Student\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    /**
     * Submit bulk attendance for a classroom on a specific date.
     */
    public function submitBulk(int $classroomId, string $date, array $records): int
    {
        return DB::transaction(function () use ($classroomId, $date, $records) {
            $count = 0;

            foreach ($records as $studentId => $data) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'attendance_date' => $date,
                    ],
                    [
                        'classroom_id' => $classroomId,
                        'status' => $data['status'] ?? Attendance::STATUS_ABSENT,
                        'note' => $data['note'] ?? null,
                    ]
                );
                $count++;
            }

            return $count;
        });
    }

    /**
     * Get daily recap for a classroom.
     */
    public function getDailyRecap(int $classroomId, string $date): Collection
    {
        return Attendance::with('student')
            ->where('classroom_id', $classroomId)
            ->where('attendance_date', $date)
            ->get()
            ->groupBy('status');
    }

    /**
     * Get monthly recap for a student.
     */
    public function getMonthlyRecap(int $studentId, string $month, string $year): array
    {
        $attendances = Attendance::where('student_id', $studentId)
            ->whereYear('attendance_date', $year)
            ->whereMonth('attendance_date', $month)
            ->get();

        return [
            'present' => $attendances->where('status', Attendance::STATUS_PRESENT)->count(),
            'sick' => $attendances->where('status', Attendance::STATUS_SICK)->count(),
            'permission' => $attendances->where('status', Attendance::STATUS_PERMISSION)->count(),
            'absent' => $attendances->where('status', Attendance::STATUS_ABSENT)->count(),
            'late' => $attendances->where('status', Attendance::STATUS_LATE)->count(),
            'total' => $attendances->count(),
        ];
    }

    /**
     * Get attendance rate for a student in a period.
     */
    public function getAttendanceRate(int $studentId, string $startDate, string $endDate): float
    {
        $total = Attendance::where('student_id', $studentId)
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->count();

        $present = Attendance::where('student_id', $studentId)
            ->whereBetween('attendance_date', [$startDate, $endDate])
            ->whereIn('status', [Attendance::STATUS_PRESENT, Attendance::STATUS_LATE])
            ->count();

        if ($total === 0) {
            return 0;
        }

        return round(($present / $total) * 100, 2);
    }
}
