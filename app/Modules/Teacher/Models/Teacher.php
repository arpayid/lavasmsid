<?php

namespace App\Modules\Teacher\Models;

use App\Models\User;
use App\Modules\Academic\Models\Subject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'nip', 'nuptk', 'name', 'gender', 'birth_place',
        'birth_date', 'email', 'phone', 'address', 'qualification',
        'certification_number', 'status', 'photo_path',
    ];

    protected $casts = ['birth_date' => 'date'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects')
            ->withPivot(['classroom_id', 'academic_year_id', 'semester_id'])
            ->withTimestamps();
    }
}
