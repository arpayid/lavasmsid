<?php

namespace App\Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;

class CertificationResult extends Model
{
    protected $table = 'certification_results';

    protected $fillable = [
        'certification_id',
        'student_id',
        'semester_id',
        'result',
        'score',
    ];

    protected $casts = [
        'score' => 'decimal:2',
    ];

    public function certification()
    {
        return $this->belongsTo(Certification::class);
    }

    public function student()
    {
        return $this->belongsTo(\App\Modules\Student\Models\Student::class);
    }
}
