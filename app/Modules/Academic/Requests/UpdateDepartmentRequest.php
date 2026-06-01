<?php

namespace App\Modules\Academic\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDepartmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('academic.update');
    }

    public function rules(): array
    {
        $id = $this->route('department')->id;

        return [
            'code' => ['required', 'string', 'max:20', Rule::unique('departments')->ignore($id)],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }
}
