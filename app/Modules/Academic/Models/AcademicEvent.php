<?php

namespace App\Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AcademicEvent extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'description', 'start_date', 'end_date', 'type', 'academic_year_id', 'semester_id', 'is_public'];

    protected $casts = ['start_date' => 'date', 'end_date' => 'date', 'is_public' => 'boolean'];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }
}
