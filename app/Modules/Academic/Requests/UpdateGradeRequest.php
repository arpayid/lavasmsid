<?php

namespace App\Modules\Academic\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('grade.update') ?? false;
    }

    public function rules(): array
    {
        return app(StoreGradeRequest::class)->rules();
    }
}
