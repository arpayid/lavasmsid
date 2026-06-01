<?php

namespace App\Modules\Academic\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('attendance.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'attendance_date' => ['required', 'date'],
            'records' => ['required', 'array'],
            'records.*.student_id' => ['required', 'exists:students,id'],
            'records.*.status' => ['required', 'in:present,sick,permission,absent,late'],
            'records.*.note' => ['nullable', 'string'],
        ];
    }
}
