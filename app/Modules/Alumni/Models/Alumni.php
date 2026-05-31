<?php

namespace App\Modules\Alumni\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alumni extends Model
{
    const STATUS_UNEMPLOYED = 'unemployed';
    const STATUS_WORKING = 'working';
    const STATUS_STUDYING = 'studying';
    const STATUS_ENTREPRENEUR = 'entrepreneur';

    protected $table = 'alumni';

    protected $fillable = [
        'student_id',
        'department_id',
        'name',
        'nis',
        'graduation_year',
        'status',
        'company_name',
        'job_title',
        'salary_range',
        'institution_name',
        'study_program',
        'email',
        'phone',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Student\Models\Student::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Academic\Models\Department::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(\App\Modules\Alumni\Models\JobApplication::class);
    }
}
