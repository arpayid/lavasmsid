<?php

namespace App\Modules\Academic\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompetencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('academic.update');
    }

    public function rules(): array
    {
        $id = $this->route('competency')->id;

        return [
            'department_id' => ['required', 'exists:departments,id'],
            'code' => ['required', 'string', 'max:20', Rule::unique('competencies')->ignore($id)],
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
