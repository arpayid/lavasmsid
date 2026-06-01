<?php

namespace App\Modules\Staff\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('staff.update') ?? false;
    }

    public function rules(): array
    {
        $id = $this->route('staff')->id;

        return [
            'employee_number' => ['nullable', 'string', 'max:50', Rule::unique('staff')->ignore($id)],
            'name' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:L,P'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('staff')->ignore($id)],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'in:active,inactive'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ];
    }
}
