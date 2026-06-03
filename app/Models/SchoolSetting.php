<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;

class SchoolSetting extends Model
{
    protected $fillable = [
        'school_name',
        'tagline',
        'school_email',
        'school_phone',
        'school_address',
        'principal_name',
        'principal_message',
        'history',
        'vision',
        'mission',
        'website_url',
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'logo_path',
        'favicon_path',
    ];

    public static function firstOrCreateDefault(): self
    {
        $defaults = ['school_name' => config('app.name', 'LavaSMSID')];

        try {
            if (! Schema::hasTable('school_settings')) {
                return new self($defaults);
            }

            return self::firstOrCreate(['id' => 1], $defaults);
        } catch (QueryException) {
            return new self($defaults);
        }
    }
}
