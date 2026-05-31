<?php

namespace App\Modules\Alumni\Models;

use App\Modules\IndustryPartner\Models\IndustryPartner;
use Illuminate\Database\Eloquent\Model;

class JobVacancy extends Model
{
    protected $fillable = [
        'title',
        'industry_partner_id',
        'company_name',
        'description',
        'location',
        'salary_range',
        'status',
        'deadline',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    public function industryPartner()
    {
        return $this->belongsTo(IndustryPartner::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
