<?php

namespace App\Modules\Academic\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    protected $fillable = [
        'department_id',
        'name',
        'level',
        'room',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}