<?php

namespace App\Modules\Academic\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('schedule.update') ?? false;
    }

    public function rules(): array
    {
        return app(StoreScheduleRequest::class)->rules();
    }
}
