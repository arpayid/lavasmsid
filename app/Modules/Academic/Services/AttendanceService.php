<?php

namespace App\Modules\Academic\Services;

use App\Modules\Academic\Models\Attendance;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    /**
     * Submit bulk attendance. Uses updateOrCreate for idempotency.
     * One record per student per date.
     */
    public function submitBulk(array $data): int
    {
        return DB::transaction(function () use ($data) {
            $count = 0;
            foreach ($data['records'] as $record) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $record['student_id'],
                        'attendance_date' => $data['attendance_date'],
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
