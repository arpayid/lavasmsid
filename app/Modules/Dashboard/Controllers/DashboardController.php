<?php

namespace App\Modules\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Get counts for dashboard stats (mocked for now, until models are built in Phase 4/5/7)
        $stats = [
            'students' => 0,
            'teachers' => 0,
            'departments' => 0,
            'ppdb' => 0,
            'payments_today' => 0,
            'attendance_today' => 0,
        ];

        return view('admin.dashboard', [
            'stats' => $stats,
        ]);
    }
}
