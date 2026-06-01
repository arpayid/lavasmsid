<?php

namespace App\Modules\Academic\Models;

use App\Modules\Student\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = [
        'student_id', 'subject_id', 'semester_id',
        'assignment_score', 'midterm_score', 'final_score', 'practice_score',
        'final_result', 'grade_letter', 'predicate', 'note',
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
        return $this->belongsTo(Student::class);
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
     * Calculate weighted score: assignment 30%, midterm 25%, final 35%, practice 10%.
     * If components are null, calculate proportionally from available components.
     */
    public function calculateFinalResult(): float
    {
        $weights = [
            'assignment_score' => 0.30,
            'midterm_score' => 0.25,
            'final_score' => 0.35,
            'practice_score' => 0.10,
        ];

        $totalWeight = 0;
        $weightedSum = 0;

        foreach ($weights as $field => $weight) {
            $score = $this->{$field};
            if ($score !== null && $score > 0) {
                $weightedSum += $score * $weight;
                $totalWeight += $weight;
            }
        }

        if ($totalWeight === 0) {
            return 0;
        }

        return round($weightedSum / $totalWeight, 2);
    }

    /**
     * Get grade letter based on score.
     * >=90 A, >=80 B, >=70 C, >=60 D, <60 E
     */
    public static function getGradeLetter(float $score): string
    {
        return match (true) {
            $score >= 90 => 'A',
            $score >= 80 => 'B',
            $score >= 70 => 'C',
            $score >= 60 => 'D',
            default => 'E',
        };
    }

    /**
     * Get predicate based on score.
     */
    public static function getPredicate(float $score): string
    {
        return match (true) {
            $score >= 90 => 'Sangat Baik',
            $score >= 80 => 'Baik',
            $score >= 70 => 'Cukup',
            $score >= 60 => 'Kurang',
            default => 'Sangat Kurang',
        };
    }
}
