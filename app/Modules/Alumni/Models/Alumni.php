<?php

namespace App\Modules\Alumni\Models;

use App\Modules\Academic\Models\Department;
use App\Modules\Student\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alumni extends Model
{
    const STATUS_UNEMPLOYED = 'unemployed';

    const STATUS_WORKING = 'working';

    const STATUS_STUDYING = 'studying';

    const STATUS_ENTREPRENEUR = 'entrepreneur';

    const STATUS_UNKNOWN = 'unknown';

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
        'address',
        'notes',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }
}
