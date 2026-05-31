<?php

namespace App\Modules\Communication\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['title', 'content', 'target', 'priority', 'is_published', 'published_at', 'created_by'];
    protected $casts = ['is_published' => 'boolean', 'published_at' => 'datetime'];

    public function creator() { return $this->belongsTo(\App\Models\User::class, 'created_by'); }
}
