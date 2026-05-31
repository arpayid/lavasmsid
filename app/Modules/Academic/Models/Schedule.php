<?php

namespace App\Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = [
        'classroom_id',
        'subject_id',
        'teacher_id',
        'day',
        'start_time',
        'end_time',
        'room',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * Check for schedule conflicts.
     * Returns true if there's a conflict.
     */
    public function hasConflict(): bool
    {
        $query = self::where('classroom_id', $this->classroom_id)
            ->where('day', $this->day)
            ->where('id', '!=', $this->id ?? 0)
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->where('start_time', '<', $this->end_time)
                        ->where('end_time', '>', $this->start_time);
                });
            });

        return $query->exists();
    }

    /**
     * Check for teacher conflicts.
     */
    public function hasTeacherConflict(): bool
    {
        if (! $this->teacher_id) {
            return false;
        }

        $query = self::where('teacher_id', $this->teacher_id)
            ->where('day', $this->day)
            ->where('id', '!=', $this->id ?? 0)
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->where('start_time', '<', $this->end_time)
                        ->where('end_time', '>', $this->start_time);
                });
            });

        return $query->exists();
    }

    /**
     * Check for room conflicts.
     */
    public function hasRoomConflict(): bool
    {
        if (! $this->room) {
            return false;
        }

        $query = self::where('room', $this->room)
            ->where('day', $this->day)
            ->where('id', '!=', $this->id ?? 0)
            ->where(function ($q) {
                $q->where(function ($q2) {
                    $q2->where('start_time', '<', $this->end_time)
                        ->where('end_time', '>', $this->start_time);
                });
            });

        return $query->exists();
    }
}
