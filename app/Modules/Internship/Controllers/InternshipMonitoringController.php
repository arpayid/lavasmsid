<?php

namespace App\Modules\Internship\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Internship\Models\Internship;
use App\Modules\Internship\Models\InternshipMonitoring;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class InternshipMonitoringController extends Controller
{
    public function index(Request $request): View
    {
        if (Gate::denies('internship.view')) {
            abort(403);
        }

        $query = InternshipMonitoring::with(['internship.student', 'teacher']);

        if ($request->filled('internship_id')) {
            $query->where('internship_id', $request->internship_id);
        }

        $monitorings = $query->latest('monitor_date')->paginate(20);

        return view('modules.internship.monitorings.index', compact('monitorings'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (Gate::denies('internship.create')) {
            abort(403);
        }

        $validated = $request->validate([
            'internship_id' => ['required', 'exists:internships,id'],
            'monitor_date' => ['required', 'date'],
            'note' => ['required', 'string'],
            'follow_up' => ['nullable', 'string'],
        ]);

        $internship = Internship::findOrFail($validated['internship_id']);

        if ($internship->status === 'cancelled' || $internship->status === 'planned') {
            return back()->with('error', 'Tidak dapat menambah monitoring untuk PKL yang dibatalkan atau belum dimulai.');
        }

        InternshipMonitoring::create($validated);

        return back()->with('success', 'Data monitoring berhasil disimpan.');
    }
}
