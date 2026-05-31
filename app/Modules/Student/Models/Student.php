<?php

namespace App\Modules\Student\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'department_id', 'classroom_id', 'nis', 'nisn', 'name', 'gender', 'birth_date', 'phone', 'address', 'status', 'photo_path'
    ];

    protected $casts = ['birth_date' => 'date'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Academic\Models\Department::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(\App\Modules\Academic\Models\Classroom::class);
    }
}
