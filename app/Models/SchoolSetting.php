<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    protected $fillable = [
        'school_name',
        'school_email',
        'school_phone',
        'school_address',
        'logo_path',
    ];

    public static function firstOrCreateDefault(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            ['school_name' => config('app.name', 'LavaSMSID')]
        );
    }
}
