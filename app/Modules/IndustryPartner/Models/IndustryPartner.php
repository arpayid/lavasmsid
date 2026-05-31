<?php

namespace App\Modules\IndustryPartner\Models;

use App\Modules\Internship\Models\Internship;
use Illuminate\Database\Eloquent\Model;

class IndustryPartner extends Model
{
    protected $fillable = [
        'name',
        'sector',
        'contact_person',
        'phone',
        'address',
    ];

    public function internships()
    {
        return $this->hasMany(Internship::class);
    }
}
