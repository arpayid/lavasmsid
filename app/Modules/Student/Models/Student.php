<?php

namespace App\Modules\Student\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'department_id', 'classroom_id', 'nis', 'nisn', 'name', 'gender', 'birth_place', 'birth_date', 'phone', 'address', 'status', 'photo_path'
    ];

    protected $casts = ['birth_date' => 'date'];
}
