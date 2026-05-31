<?php

namespace App\Modules\PPDB\Models;

use App\Modules\Academic\Models\Department;
use Illuminate\Database\Eloquent\Model;

class PpdbRegistration extends Model
{
    const STATUS_SUBMITTED = 'submitted';

    const STATUS_VERIFIED = 'verified';

    const STATUS_ACCEPTED = 'accepted';

    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'registration_number',
        'department_id',
        'candidate_name',
        'email',
        'phone',
        'address',
        'birth_place',
        'birth_date',
        'gender',
        'previous_school',
        'parent_name',
        'parent_phone',
        'document_path',
        'notes',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
