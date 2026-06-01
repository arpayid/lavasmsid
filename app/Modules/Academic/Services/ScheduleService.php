<?php

namespace App\Modules\Academic\Services;

use App\Modules\Academic\Models\Schedule;
use Illuminate\Support\Facades\DB;

class ScheduleService
{
    public function create(array $data): Schedule
    {
        return DB::transaction(fn () => Schedule::create($data));
    }

    public function update(Schedule $schedule, array $data): Schedule
    {
        return DB::transaction(function () use ($schedule, $data) {
            $schedule->update($data);

            return $schedule;
        });
    }

    public function delete(Schedule $schedule): bool
    {
        return $schedule->delete();
    }

    /**
     * Detect schedule conflicts.
     * Returns array of conflict messages, empty if no conflicts.
     */
    public function detectConflicts(array $data, ?int $excludeId = null): array
    {
        $conflicts = [];
        $baseQuery = Schedule::where('day', $data['day'])
            ->where(function ($q) use ($data) {
                $q->where(function ($q2) use ($data) {
                    $q2->where('start_time', '<', $data['end_time'])
                        ->where('end_time', '>', $data['start_time']);
                });
            });

        if ($excludeId) {
            $baseQuery->where('id', '!=', $excludeId);
        }

        // Classroom conflict
        $classroomConflict = (clone $baseQuery)->where('classroom_id', $data['classroom_id'])->exists();
        if ($classroomConflict) {
            $conflicts[] = 'Kelas sudah memiliki jadwal pada waktu yang sama';
        }

        // Teacher conflict
        if (! empty($data['teacher_id'])) {
            $teacherConflict = (clone $baseQuery)->where('teacher_id', $data['teacher_id'])->exists();
            if ($teacherConflict) {
                $conflicts[] = 'Guru sudah mengajar pada waktu yang sama';
            }
        }

        // Room conflict
        if (! empty($data['room'])) {
            $roomConflict = (clone $baseQuery)->where('room', $data['room'])->exists();
            if ($roomConflict) {
                $conflicts[] = 'Ruangan sudah digunakan pada waktu yang sama';
            }
        }

        return $conflicts;
    }
}
