<?php

namespace App\Modules\Academic\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BulkGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('grade.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'subject_id' => ['required', 'exists:subjects,id'],
            'semester_id' => ['required', 'exists:semesters,id'],
            'grades' => ['required', 'array'],
            'grades.*.student_id' => ['required', 'exists:students,id'],
            'grades.*.assignment_score' => ['nullable', 'numeric', 'between:0,100'],
            'grades.*.midterm_score' => ['nullable', 'numeric', 'between:0,100'],
            'grades.*.final_score' => ['nullable', 'numeric', 'between:0,100'],
            'grades.*.practice_score' => ['nullable', 'numeric', 'between:0,100'],
        ];
    }
}
