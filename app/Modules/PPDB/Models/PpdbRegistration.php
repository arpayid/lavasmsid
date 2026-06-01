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

    const STATUS_CONVERTED = 'converted';

    protected $fillable = [
        'registration_number',
        'ppdb_period_id',
        'department_id',
        'chosen_department_id',
        'chosen_classroom_id',
        'nisn',
        'name',
        'candidate_name',
        'email',
        'phone',
        'address',
        'birth_place',
        'birth_date',
        'gender',
        'religion',
        'previous_school',
        'parent_name',
        'parent_phone',
        'document_path',
        'notes',
        'verification_note',
        'status',
        'accepted_at',
        'converted_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'accepted_at' => 'datetime',
        'converted_at' => 'datetime',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function period()
    {
        return $this->belongsTo(PpdbPeriod::class, 'ppdb_period_id');
    }
}
