<?php

namespace App\Modules\Internship\Models;

use App\Models\User;
use App\Modules\IndustryPartner\Models\IndustryPartner;
use App\Modules\Student\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Internship extends Model
{
    const STATUS_PLANNED = 'planned';

    const STATUS_ONGOING = 'ongoing';

    const STATUS_COMPLETED = 'completed';

    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'student_id',
        'industry_partner_id',
        'teacher_id',
        'start_date',
        'end_date',
        'status',
        'grade',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'grade' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function industryPartner(): BelongsTo
    {
        return $this->belongsTo(IndustryPartner::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
