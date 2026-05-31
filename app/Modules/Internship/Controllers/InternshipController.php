<?php

namespace App\Modules\Internship\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Internship\Models\Internship;
use App\Modules\IndustryPartner\Models\IndustryPartner;
use App\Modules\Student\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InternshipController extends Controller
{
    public function index(Request $request): View
    {
        $query = Internship::with(['student', 'industryPartner'])
            ->orderByDesc('start_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $internships = $query->paginate(20);

        return view('modules.internship.index', compact('internships'));
    }

    public function create(): View
    {
        return view('modules.internship.create', [
            'students' => Student::orderBy('name')->get(),
            'partners' => IndustryPartner::orderBy('name')->get(),
            'statuses' => ['planned', 'ongoing', 'completed', 'cancelled'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'industry_partner_id' => ['required', 'exists:industry_partners,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'status' => ['required', 'in:planned,ongoing,completed,cancelled'],
            'grade' => ['nullable', 'numeric', 'between:0,100'],
            'notes' => ['nullable', 'string'],
        ]);

        Internship::create($validated);

        return redirect()->route('admin.internships.index')
            ->with('success', 'Data PKL berhasil ditambahkan.');
    }

    public function edit(Internship $internship): View
    {
        return view('modules.internship.edit', [
            'internship' => $internship,
            'students' => Student::orderBy('name')->get(),
            'partners' => IndustryPartner::orderBy('name')->get(),
            'statuses' => ['planned', 'ongoing', 'completed', 'cancelled'],
        ]);
    }

    public function update(Request $request, Internship $internship): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'industry_partner_id' => ['required', 'exists:industry_partners,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'status' => ['required', 'in:planned,ongoing,completed,cancelled'],
            'grade' => ['nullable', 'numeric', 'between:0,100'],
            'notes' => ['nullable', 'string'],
        ]);

        $internship->update($validated);

        return redirect()->route('admin.internships.index')
            ->with('success', 'Data PKL berhasil diperbarui.');
    }

    public function destroy(Internship $internship): RedirectResponse
    {
        $internship->delete();

        return redirect()->route('admin.internships.index')
            ->with('success', 'Data PKL berhasil dihapus.');
    }
}
