<?php

namespace App\Modules\Alumni\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    const STATUS_APPLIED = 'applied';

    const STATUS_SCREENING = 'screening';

    const STATUS_INTERVIEW = 'interview';

    const STATUS_HIRED = 'hired';

    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'alumni_id',
        'job_vacancy_id',
        'status',
        'applied_at',
        'notes',
    ];

    protected $casts = [
        'applied_at' => 'date',
    ];

    public function alumni(): BelongsTo
    {
        return $this->belongsTo(Alumni::class);
    }

    public function vacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id');
    }

    // Keep for backward compatibility if needed
    public function jobVacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id');
    }
}
