<?php

namespace App\Modules\Academic\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompetencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('academic.create');
    }

    public function rules(): array
    {
        return [
            'department_id' => ['required', 'exists:departments,id'],
            'code' => ['required', 'string', 'max:20', 'unique:competencies,code'],
            'name' => ['required', 'string', 'max:255'],
        ];
    }
}
