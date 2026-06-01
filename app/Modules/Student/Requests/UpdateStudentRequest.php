<?php

namespace App\Modules\Student\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('student.update') ?? false;
    }

    public function rules(): array
    {
        $id = $this->route('student')->id;

        return [
            'nis' => ['required', 'string', 'max:50', Rule::unique('students')->ignore($id)],
            'nisn' => ['nullable', 'string', 'max:50', Rule::unique('students')->ignore($id)],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:L,P'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'religion' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'classroom_id' => ['nullable', 'exists:classrooms,id'],
            'status' => ['required', 'in:active,graduated,moved,dropped'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ];
    }
}
