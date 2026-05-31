<?php

namespace App\Modules\Website\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = ['title', 'student_name', 'department_id', 'level', 'position', 'year', 'description'];
}
