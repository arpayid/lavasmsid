<?php

namespace App\Modules\Teacher\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('teacher.update') ?? false;
    }

    public function rules(): array
    {
        $id = $this->route('teacher')->id;

        return [
            'nip' => ['nullable', 'string', 'max:50', Rule::unique('teachers')->ignore($id)],
            'nuptk' => ['nullable', 'string', 'max:30', Rule::unique('teachers')->ignore($id)],
            'name' => ['required', 'string', 'max:255'],
            'gender' => ['nullable', 'in:L,P'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('teachers')->ignore($id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'qualification' => ['nullable', 'string', 'max:100'],
            'certification_number' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:active,inactive'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ];
    }
}
