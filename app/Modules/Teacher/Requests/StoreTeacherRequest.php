<?php

namespace App\Modules\Teacher\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('teacher.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'nip' => ['nullable', 'string', 'max:50', 'unique:teachers,nip'],
            'nuptk' => ['nullable', 'string', 'max:30', 'unique:teachers,nuptk'],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['nullable', 'in:L,P'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'email' => ['nullable', 'email', 'max:255', 'unique:teachers,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'qualification' => ['nullable', 'string', 'max:100'],
            'certification_number' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:active,inactive'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'subjects' => ['nullable', 'array'],
            'subjects.*.subject_id' => ['required', 'exists:subjects,id'],
            'subjects.*.classroom_id' => ['nullable', 'exists:classrooms,id'],
            'subjects.*.academic_year_id' => ['nullable', 'exists:academic_years,id'],
            'subjects.*.semester_id' => ['nullable', 'exists:semesters,id'],
        ];
    }
}
