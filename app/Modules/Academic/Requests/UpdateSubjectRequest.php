<?php

namespace App\Modules\Academic\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('academic.update');
    }

    public function rules(): array
    {
        $id = $this->route('subject')->id;

        return [
            'department_id' => ['nullable', 'exists:departments,id'],
            'code' => ['required', 'string', 'max:20', Rule::unique('subjects')->ignore($id)],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'in:general,productive,vocational'],
        ];
    }
}
