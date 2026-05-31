<?php

namespace App\Modules\Academic\Models;

use App\Modules\Student\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    const STATUS_PRESENT = 'present';

    const STATUS_SICK = 'sick';

    const STATUS_PERMISSION = 'permission';

    const STATUS_ABSENT = 'absent';

    const STATUS_LATE = 'late';

    protected $fillable = [
        'student_id',
        'classroom_id',
        'attendance_date',
        'status',
        'note',
    ];

    protected $casts = [
        'attendance_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }
}
