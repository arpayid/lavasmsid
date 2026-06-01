<?php

namespace App\Modules\PPDB\Models;

use App\Modules\Academic\Models\AcademicYear;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PpdbPeriod extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'academic_year_id', 'name', 'start_date', 'end_date', 'quota', 'status', 'description', 'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(PpdbRegistration::class, 'ppdb_period_id');
    }

    public function isOpen(): bool
    {
        return $this->status === 'open' && $this->is_active;
    }
}
