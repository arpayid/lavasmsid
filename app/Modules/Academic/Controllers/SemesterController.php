<?php

namespace App\Modules\Academic\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\AcademicYear;
use App\Modules\Academic\Models\Semester;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SemesterController extends Controller
{
    public function index(Request $request): View
    {
        $query = Semester::with('academicYear');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $semesters = $query->orderByDesc('id')->paginate(15);

        return view('modules.academic.semesters.index', [
            'semesters' => $semesters,
        ]);
    }

    public function create(): View
    {
        $academicYears = AcademicYear::orderBy('name')->get();

        return view('modules.academic.semesters.create', [
            'academicYears' => $academicYears,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);

        Semester::create($validated);

        return redirect()->route('admin.semesters.index')
            ->with('success', 'Semester berhasil ditambahkan.');
    }

    public function edit(Semester $semester): View
    {
        $academicYears = AcademicYear::orderBy('name')->get();

        return view('modules.academic.semesters.edit', [
            'semester' => $semester,
            'academicYears' => $academicYears,
        ]);
    }

    public function update(Request $request, Semester $semester): RedirectResponse
    {
        $validated = $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', false);

        $semester->update($validated);

        return redirect()->route('admin.semesters.index')
            ->with('success', 'Semester berhasil diperbarui.');
    }

    public function destroy(Semester $semester): RedirectResponse
    {
        $semester->delete();

        return redirect()->route('admin.semesters.index')
            ->with('success', 'Semester berhasil dihapus.');
    }
}
