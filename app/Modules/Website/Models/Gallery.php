<?php

namespace App\Modules\Website\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = ['title', 'image_path', 'category', 'description', 'sort_order'];
}
