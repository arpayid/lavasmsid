<?php

namespace App\Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'subject_id',
        'semester_id',
        'assignment_score',
        'midterm_score',
        'final_score',
        'practice_score',
        'final_result',
    ];

    protected $casts = [
        'assignment_score' => 'decimal:2',
        'midterm_score' => 'decimal:2',
        'final_score' => 'decimal:2',
        'practice_score' => 'decimal:2',
        'final_result' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Student\Models\Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Calculate final result from scores.
     * Default formula: (assignment + midterm + final + practice) / 4
     */
    public function calculateFinalResult(): float
    {
        $scores = array_filter([
            $this->assignment_score,
            $this->midterm_score,
            $this->final_score,
            $this->practice_score,
        ]);

        if (empty($scores)) {
            return 0;
        }

        return round(array_sum($scores) / count($scores), 2);
    }
}