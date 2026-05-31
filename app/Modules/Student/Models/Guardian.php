<?php

namespace App\Modules\Student\Models;

use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    protected $fillable = [
        'student_id',
        'name',
        'relationship',
        'phone',
        'email',
        'address',
        'occupation',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
