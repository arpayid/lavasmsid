<?php

namespace App\Modules\Internship\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Internship\Models\Internship;
use App\Modules\Internship\Models\InternshipLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class InternshipLogController extends Controller
{
    public function index(Request $request): View
    {
        if (Gate::denies('internship.view')) {
            abort(403);
        }

        $query = InternshipLog::with(['internship.student', 'reviewer']);

        if ($request->filled('internship_id')) {
            $query->where('internship_id', $request->internship_id);
        }

        $logs = $query->latest('activity_date')->paginate(20);

        return view('modules.internship.logs.index', compact('logs'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (Gate::denies('internship.create')) {
            abort(403);
        }

        $validated = $request->validate([
            'internship_id' => ['required', 'exists:internships,id'],
            'activity_date' => ['required', 'date'],
            'activity' => ['required', 'string', 'max:500'],
            'result' => ['nullable', 'string'],
            'obstacle' => ['nullable', 'string'],
        ]);

        $internship = Internship::findOrFail($validated['internship_id']);

        if ($internship->status === 'cancelled') {
            return back()->with('error', 'Tidak dapat menambah logbook untuk PKL yang dibatalkan.');
        }

        if ($validated['activity_date'] < $internship->start_date->toDateString() || $validated['activity_date'] > $internship->end_date->toDateString()) {
            return back()->with('error', 'Tanggal kegiatan harus berada di dalam periode PKL.');
        }

        InternshipLog::create($validated);

        return back()->with('success', 'Logbook harian berhasil disimpan.');
    }

    public function review(Request $request, InternshipLog $internshipLog): RedirectResponse
    {
        if (Gate::denies('internship.update')) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:reviewed,rejected'],
            'note' => ['nullable', 'string'],
        ]);

        $internshipLog->update([
            'status' => $validated['status'],
            'note' => $validated['note'],
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Logbook berhasil direview.');
    }
}
