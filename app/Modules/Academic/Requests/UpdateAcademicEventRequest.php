<?php

namespace App\Modules\Academic\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAcademicEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('academic.update') ?? false;
    }

    public function rules(): array
    {
        return app(StoreAcademicEventRequest::class)->rules();
    }
}
