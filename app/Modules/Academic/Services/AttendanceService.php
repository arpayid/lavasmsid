<?php

namespace App\Modules\Academic\Services;

use App\Modules\Academic\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function submitBulk(array $data): int
    {
        return DB::transaction(function () use ($data) {
            $count = 0;
            $attendanceDate = Carbon::parse($data['attendance_date'])->startOfDay();

            foreach ($data['records'] as $record) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $record['student_id'],
                        'attendance_date' => $attendanceDate,
                    ],
                    [
                        'classroom_id' => $data['classroom_id'],
                        'status' => $record['status'],
                        'note' => $record['note'] ?? null,
                    ]
                );
                $count++;
            }

            return $count;
        });
    }
}
