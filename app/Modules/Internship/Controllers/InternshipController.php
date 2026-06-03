<?php

namespace App\Modules\Internship\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\IndustryPartner\Models\IndustryPartner;
use App\Modules\Internship\Models\Internship;
use App\Modules\Student\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class InternshipController extends Controller
{
    public function dashboard(): View
    {
        if (Gate::denies('internship.view')) {
            abort(403);
        }

        $totalPartners = IndustryPartner::count();
        $totalInternships = Internship::count();
        $plannedInternships = Internship::where('status', 'planned')->count();
        $ongoingInternships = Internship::where('status', 'ongoing')->count();
        $completedInternships = Internship::where('status', 'completed')->count();
        $recentInternships = Internship::with(['student', 'industryPartner'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('modules.internship.dashboard', compact(
            'totalPartners',
            'totalInternships',
            'plannedInternships',
            'ongoingInternships',
            'completedInternships',
            'recentInternships'
        ));
    }

    public function index(Request $request): View
    {
        if (Gate::denies('internship.view')) {
            abort(403);
        }

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
        if (Gate::denies('internship.create')) {
            abort(403);
        }

        return view('modules.internship.create', [
            'students' => Student::orderBy('name')->get(),
            'partners' => IndustryPartner::orderBy('name')->get(),
            'statuses' => ['planned', 'ongoing', 'completed', 'cancelled'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (Gate::denies('internship.create')) {
            abort(403);
        }

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'industry_partner_id' => ['required', 'exists:industry_partners,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:planned,ongoing,completed,cancelled'],
            'grade' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $student = Student::find($validated['student_id']);
        if ($student && $student->internships()->active()->exists()) {
            return back()->withInput()
                ->with('error', 'Siswa ini sudah memiliki PKL aktif (planned/ongoing).');
        }

        Internship::create($validated);

        return redirect()->route('admin.internships.index')
            ->with('success', 'Data PKL berhasil ditambahkan.');
    }

    public function show(Internship $internship): View
    {
        if (Gate::denies('internship.view')) {
            abort(403);
        }

        $internship->load(['student', 'industryPartner', 'user', 'score']);

        return view('modules.internship.show', compact('internship'));
    }

    public function edit(Internship $internship): View
    {
        if (Gate::denies('internship.update')) {
            abort(403);
        }

        return view('modules.internship.edit', [
            'internship' => $internship,
            'students' => Student::orderBy('name')->get(),
            'partners' => IndustryPartner::orderBy('name')->get(),
            'statuses' => ['planned', 'ongoing', 'completed', 'cancelled'],
        ]);
    }

    public function update(Request $request, Internship $internship): RedirectResponse
    {
        if (Gate::denies('internship.update')) {
            abort(403);
        }

        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'industry_partner_id' => ['required', 'exists:industry_partners,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:planned,ongoing,completed,cancelled'],
            'grade' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $newStatus = $validated['status'];
        $oldStatus = $internship->status;

        if (in_array($oldStatus, ['ongoing', 'completed']) && $validated['student_id'] != $internship->student_id) {
            return back()->withInput()
                ->with('error', 'Tidak dapat mengubah siswa pada PKL yang sedang berjalan atau sudah selesai.');
        }

        if ($newStatus !== 'planned' && $newStatus !== $oldStatus) {
            $hasActiveOther = Internship::where('student_id', $validated['student_id'])
                ->where('id', '!=', $internship->id)
                ->active()
                ->exists();

            if ($hasActiveOther) {
                return back()->withInput()
                    ->with('error', 'Siswa ini sudah memiliki PKL aktif lainnya.');
            }
        }

        $internship->update($validated);

        return redirect()->route('admin.internships.index')
            ->with('success', 'Data PKL berhasil diperbarui.');
    }

    public function destroy(Internship $internship): RedirectResponse
    {
        if (Gate::denies('internship.delete')) {
            abort(403);
        }

        if (in_array($internship->status, ['ongoing', 'completed'])) {
            return back()->with('error', 'PKL dengan status berjalan atau selesai tidak dapat dihapus.');
        }

        $internship->delete();

        return redirect()->route('admin.internships.index')
            ->with('success', 'Data PKL berhasil dihapus.');
    }
}
