<?php

namespace App\Modules\Alumni\Models;

use App\Modules\IndustryPartner\Models\IndustryPartner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobVacancy extends Model
{
    const STATUS_DRAFT = 'draft';

    const STATUS_ACTIVE = 'active';

    const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'industry_partner_id',
        'title',
        'company_name',
        'location',
        'type',
        'description',
        'requirements',
        'salary_min',
        'salary_max',
        'salary_range', // Keep for backward compatibility if needed, but we'll use min/max
        'deadline',
        'status',
    ];

    protected $casts = [
        'deadline' => 'date',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
    ];

    public function industryPartner(): BelongsTo
    {
        return $this->belongsTo(IndustryPartner::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }
}
