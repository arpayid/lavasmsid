<?php

namespace App\Modules\Internship\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternshipMonitoring extends Model
{
    protected $fillable = [
        'internship_id',
        'monitor_date',
        'teacher_id',
        'note',
        'follow_up',
        'status',
    ];

    protected $casts = [
        'monitor_date' => 'date',
    ];

    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }
}
