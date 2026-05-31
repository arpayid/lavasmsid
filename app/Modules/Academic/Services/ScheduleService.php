<?php

namespace App\Modules\Academic\Services;

use App\Modules\Academic\Models\Schedule;
use Illuminate\Support\Facades\DB;

class ScheduleService
{
    /**
     * Create schedule with conflict detection.
     */
    public function create(array $data): Schedule
    {
        return DB::transaction(function () use ($data) {
            $schedule = new Schedule($data);

            $conflicts = $this->detectConflicts($schedule);

            if (! empty($conflicts)) {
                throw new \InvalidArgumentException(implode(', ', $conflicts));
            }

            $schedule->save();

            return $schedule;
        });
    }

    /**
     * Update schedule with conflict detection.
     */
    public function update(Schedule $schedule, array $data): Schedule
    {
        return DB::transaction(function () use ($schedule, $data) {
            $schedule->fill($data);

            $conflicts = $this->detectConflicts($schedule);

            if (! empty($conflicts)) {
                throw new \InvalidArgumentException(implode(', ', $conflicts));
            }

            $schedule->save();

            return $schedule;
        });
    }

    /**
     * Detect all types of conflicts.
     */
    public function detectConflicts(Schedule $schedule): array
    {
        $conflicts = [];

        // Classroom conflict
        $classroomConflict = Schedule::where('classroom_id', $schedule->classroom_id)
            ->where('day', $schedule->day)
            ->where('id', '!=', $schedule->id ?? 0)
            ->whereTime('start_time', '<', $schedule->end_time)
            ->whereTime('end_time', '>', $schedule->start_time)
            ->exists();

        if ($classroomConflict) {
            $conflicts[] = 'Jadwal bentrok dengan kelas lain di kelas yang sama';
        }

        // Teacher conflict
        if ($schedule->teacher_id) {
            $teacherConflict = Schedule::where('teacher_id', $schedule->teacher_id)
                ->where('day', $schedule->day)
                ->where('id', '!=', $schedule->id ?? 0)
                ->whereTime('start_time', '<', $schedule->end_time)
                ->whereTime('end_time', '>', $schedule->start_time)
                ->exists();

            if ($teacherConflict) {
                $conflicts[] = 'Jadwal bentrok dengan guru lain';
            }
        }

        // Room conflict
        if ($schedule->room) {
            $roomConflict = Schedule::where('room', $schedule->room)
                ->where('day', $schedule->day)
                ->where('id', '!=', $schedule->id ?? 0)
                ->whereTime('start_time', '<', $schedule->end_time)
                ->whereTime('end_time', '>', $schedule->start_time)
                ->exists();

            if ($roomConflict) {
                $conflicts[] = 'Jadwal bentrok di ruang yang sama';
            }
        }

        return $conflicts;
    }

    /**
     * Get schedules by classroom and day.
     */
    public function getByClassroomAndDay(int $classroomId, string $day)
    {
        return Schedule::with(['subject', 'classroom'])
            ->where('classroom_id', $classroomId)
            ->where('day', $day)
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Get weekly schedule for a classroom.
     */
    public function getWeeklySchedule(int $classroomId)
    {
        return Schedule::with(['subject', 'classroom'])
            ->where('classroom_id', $classroomId)
            ->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');
    }
}
