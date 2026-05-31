<?php

namespace App\Modules\Website\Models;

use Illuminate\Database\Eloquent\Model;

class CmsPage extends Model
{
    protected $fillable = ['slug', 'title', 'content'];
}
