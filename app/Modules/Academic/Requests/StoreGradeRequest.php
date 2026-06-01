<?php

namespace App\Modules\Academic\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('grade.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:students,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'semester_id' => ['required', 'exists:semesters,id'],
            'assignment_score' => ['nullable', 'numeric', 'between:0,100'],
            'midterm_score' => ['nullable', 'numeric', 'between:0,100'],
            'final_score' => ['nullable', 'numeric', 'between:0,100'],
            'practice_score' => ['nullable', 'numeric', 'between:0,100'],
            'note' => ['nullable', 'string'],
        ];
    }
}
