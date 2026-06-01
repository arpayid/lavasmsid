<?php

namespace App\Modules\PPDB\Services;

use App\Modules\PPDB\Models\PpdbPeriod;
use App\Modules\PPDB\Models\PpdbRegistration;

class PpdbDashboardService
{
    public function summary(): array
    {
        $activePeriod = PpdbPeriod::query()->where('is_active', true)->latest()->first();
        $query = PpdbRegistration::query();

        if ($activePeriod) {
            $query->where('ppdb_period_id', $activePeriod->id);
        }

        return [
            'activePeriod' => $activePeriod,
            'total' => (clone $query)->count(),
            'submitted' => (clone $query)->where('status', 'submitted')->count(),
            'verified' => (clone $query)->where('status', 'verified')->count(),
            'accepted' => (clone $query)->where('status', 'accepted')->count(),
            'rejected' => (clone $query)->where('status', 'rejected')->count(),
            'converted' => (clone $query)->where('status', 'converted')->count(),
        ];
    }
}
