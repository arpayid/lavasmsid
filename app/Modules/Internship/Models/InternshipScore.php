<?php

namespace App\Modules\Internship\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternshipScore extends Model
{
    protected $fillable = [
        'internship_id',
        'discipline_score',
        'skill_score',
        'attitude_score',
        'report_score',
        'final_score',
        'predicate',
        'assessed_by',
        'assessed_at',
        'notes',
    ];

    protected $casts = [
        'assessed_at' => 'date',
        'discipline_score' => 'decimal:2',
        'skill_score' => 'decimal:2',
        'attitude_score' => 'decimal:2',
        'report_score' => 'decimal:2',
        'final_score' => 'decimal:2',
    ];

    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }

    public static function calculatePredicate(float $finalScore): string
    {
        if ($finalScore >= 85) {
            return 'A';
        }
        if ($finalScore >= 75) {
            return 'B';
        }
        if ($finalScore >= 65) {
            return 'C';
        }

        return 'D';
    }
}
