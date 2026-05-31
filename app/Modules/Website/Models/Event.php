<?php

namespace App\Modules\Website\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    protected $fillable = ['title', 'slug', 'description', 'start_date', 'end_date', 'location', 'is_published'];

    protected $casts = ['is_published' => 'boolean', 'start_date' => 'date', 'end_date' => 'date'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->slug = Str::slug($model->title) ?: Str::random(10);
        });
    }
}
