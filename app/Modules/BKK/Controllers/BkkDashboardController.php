<?php

namespace App\Modules\BKK\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Alumni\Models\Alumni;
use App\Modules\Alumni\Models\JobVacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class BkkDashboardController extends Controller
{
    /**
     * Display the BKK dashboard.
     */
    public function index(Request $request): View
    {
        if (Gate::denies('bkk.view')) {
            abort(403);
        }

        $stats = [
            'total_alumni' => 0,
            'working_alumni' => 0,
            'studying_alumni' => 0,
            'entrepreneur_alumni' => 0,
            'total_vacancies' => 0,
            'active_vacancies' => 0,
        ];

        if (Schema::hasTable('alumni')) {
            $stats['total_alumni'] = Alumni::count();
            $stats['working_alumni'] = Alumni::where('status', 'working')->count();
            $stats['studying_alumni'] = Alumni::where('status', 'studying')->count();
            $stats['entrepreneur_alumni'] = Alumni::where('status', 'entrepreneur')->count();
        }

        if (Schema::hasTable('job_vacancies')) {
            $stats['total_vacancies'] = JobVacancy::count();
            $stats['active_vacancies'] = JobVacancy::where('status', 'active')->count();
        }

        $recentAlumni = [];
        if (Schema::hasTable('alumni')) {
            $recentAlumni = Alumni::with('department')
                ->latest()
                ->take(5)
                ->get();
        }

        return view('modules.bkk.dashboard', compact('stats', 'recentAlumni'));
    }
}
