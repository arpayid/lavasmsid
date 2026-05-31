<?php

namespace App\Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subject extends Model
{
    protected $fillable = [
        'department_id',
        'code',
        'name',
        'type',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}