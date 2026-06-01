<?php

namespace App\Modules\Academic\Services;

use App\Modules\Academic\Models\AcademicEvent;
use Illuminate\Support\Facades\DB;

class AcademicEventService
{
    public function create(array $data): AcademicEvent
    {
        return DB::transaction(fn () => AcademicEvent::create($data));
    }

    public function update(AcademicEvent $event, array $data): AcademicEvent
    {
        return DB::transaction(function () use ($event, $data) {
            $event->update($data);

            return $event;
        });
    }

    public function delete(AcademicEvent $event): bool
    {
        return $event->delete();
    }
}
