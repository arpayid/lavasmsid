<?php

namespace App\Modules\PPDB\Services;

use App\Modules\PPDB\Models\PpdbPeriod;

class PpdbOpenPeriodService
{
    public function current(): ?PpdbPeriod
    {
        return PpdbPeriod::query()
            ->where('status', 'open')
            ->where('is_active', true)
            ->latest()
            ->first();
    }
}
