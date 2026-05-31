<?php

namespace App\Modules\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'students' => 0,
                'teachers' => 0,
                'departments' => 0,
                'ppdb' => 0,
                'payments_today' => 0,
                'attendance_today' => 0,
            ],
        ]);
    }
}
