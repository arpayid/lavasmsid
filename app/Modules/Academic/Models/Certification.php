<?php

namespace App\Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    protected $fillable = [
        'name',
        'department_id',
        'level',
        'organizer',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function results()
    {
        return $this->hasMany(CertificationResult::class);
    }
}
