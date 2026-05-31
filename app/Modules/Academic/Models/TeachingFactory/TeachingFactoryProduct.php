<?php

namespace App\Modules\Academic\Models\TeachingFactory;

use App\Modules\Academic\Models\Department;
use Illuminate\Database\Eloquent\Model;

class TeachingFactoryProduct extends Model
{
    protected $table = 'teaching_factory_products';

    protected $fillable = [
        'name',
        'description',
        'department_id',
        'type',
        'status',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
