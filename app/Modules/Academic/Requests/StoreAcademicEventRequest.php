<?php

namespace App\Modules\Academic\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAcademicEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('academic.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'type' => ['required', 'in:exam,holiday,event,registration,report,other'],
            'academic_year_id' => ['nullable', 'exists:academic_years,id'],
            'semester_id' => ['nullable', 'exists:semesters,id'],
            'is_public' => ['nullable', 'boolean'],
        ];
    }
}
