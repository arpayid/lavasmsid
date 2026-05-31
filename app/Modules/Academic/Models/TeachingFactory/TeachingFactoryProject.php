<?php

namespace App\Modules\Academic\Models\TeachingFactory;

use App\Modules\Academic\Models\Department;
use Illuminate\Database\Eloquent\Model;

class TeachingFactoryProject extends Model
{
    protected $table = 'teaching_factory_projects';

    protected $fillable = [
        'name',
        'description',
        'department_id',
        'product_id',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function product()
    {
        return $this->belongsTo(TeachingFactoryProduct::class, 'product_id');
    }
}
