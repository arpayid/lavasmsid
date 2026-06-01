<?php

namespace App\Modules\Academic\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\AcademicEvent;
use App\Modules\Academic\Requests\StoreAcademicEventRequest;
use App\Modules\Academic\Requests\UpdateAcademicEventRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcademicEventController extends Controller
{
    public function index(Request $request): View
    {
        $query = AcademicEvent::with(['academicYear', 'semester']);
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('month')) {
            $query->whereMonth('start_date', $request->month);
        }
        if ($request->filled('academic_year_id')) {
            $query->where('academic_year_id', $request->academic_year_id);
        }
        $events = $query->orderBy('start_date')->paginate(15);

        return view('modules.academic.academic-events.index', compact('events'));
    }

    public function create(): View
    {
        return view('modules.academic.academic-events.create');
    }

    public function store(StoreAcademicEventRequest $request): RedirectResponse
    {
        AcademicEvent::create($request->validated());

        return redirect()->route('admin.academic-events.index')->with('success', 'Event berhasil ditambahkan.');
    }

    public function edit(AcademicEvent $academicEvent): View
    {
        return view('modules.academic.academic-events.edit', compact('academicEvent'));
    }

    public function update(UpdateAcademicEventRequest $request, AcademicEvent $academicEvent): RedirectResponse
    {
        $academicEvent->update($request->validated());

        return redirect()->route('admin.academic-events.index')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(AcademicEvent $academicEvent): RedirectResponse
    {
        $academicEvent->delete();

        return redirect()->route('admin.academic-events.index')->with('success', 'Event berhasil dihapus.');
    }
}
