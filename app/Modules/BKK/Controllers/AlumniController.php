<?php

namespace App\Modules\BKK\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Department;
use App\Modules\Alumni\Models\Alumni;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AlumniController extends Controller
{
    public function index(Request $request): View
    {
        if (Gate::denies('alumni.view')) {
            abort(403);
        }

        $query = Alumni::with('department');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('nis', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('graduation_year')) {
            $query->where('graduation_year', $request->graduation_year);
        }

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $alumni = $query->orderByDesc('graduation_year')->orderBy('name')->paginate(20);
        $departments = Department::orderBy('name')->get();

        return view('modules.bkk.alumni.index', compact('alumni', 'departments'));
    }

    public function create(): View
    {
        if (Gate::denies('alumni.create')) {
            abort(403);
        }

        $departments = Department::orderBy('name')->get();

        return view('modules.bkk.alumni.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (Gate::denies('alumni.create')) {
            abort(403);
        }

        $validated = $request->validate([
            'student_id' => ['nullable', 'exists:students,id', 'unique:alumni,student_id'],
            'name' => ['required', 'string', 'max:255'],
            'nis' => ['nullable', 'string', 'max:50'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'graduation_year' => ['required', 'integer', 'min:2000', 'max:'.(date('Y') + 1)],
            'status' => ['required', 'in:unemployed,working,studying,entrepreneur,unknown'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'salary_range' => ['nullable', 'string', 'max:50'],
            'institution_name' => ['nullable', 'string', 'max:255'],
            'study_program' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ], [
            'student_id.unique' => 'Profil alumni untuk siswa ini sudah ada.',
        ]);

        Alumni::create($validated);

        return redirect()->route('admin.bkk.alumni.index')
            ->with('success', 'Data alumni berhasil ditambahkan.');
    }

    public function show(Alumni $alumnus): View
    {
        if (Gate::denies('alumni.view')) {
            abort(403);
        }

        $alumnus->load(['department', 'student', 'applications.vacancy']);

        return view('modules.bkk.alumni.show', ['alumni' => $alumnus]);
    }

    public function edit(Alumni $alumnus): View
    {
        if (Gate::denies('alumni.update')) {
            abort(403);
        }

        $departments = Department::orderBy('name')->get();

        return view('modules.bkk.alumni.edit', ['alumni' => $alumnus, 'departments' => $departments]);
    }

    public function update(Request $request, Alumni $alumnus): RedirectResponse
    {
        if (Gate::denies('alumni.update')) {
            abort(403);
        }

        $validated = $request->validate([
            'student_id' => ['nullable', 'exists:students,id', 'unique:alumni,student_id,'.$alumnus->id],
            'name' => ['required', 'string', 'max:255'],
            'nis' => ['nullable', 'string', 'max:50'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'graduation_year' => ['required', 'integer', 'min:2000', 'max:'.(date('Y') + 1)],
            'status' => ['required', 'in:unemployed,working,studying,entrepreneur,unknown'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'salary_range' => ['nullable', 'string', 'max:50'],
            'institution_name' => ['nullable', 'string', 'max:255'],
            'study_program' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $alumnus->update($validated);

        return redirect()->route('admin.bkk.alumni.index')
            ->with('success', 'Data alumni berhasil diperbarui.');
    }

    public function destroy(Alumni $alumnus): RedirectResponse
    {
        if (Gate::denies('alumni.delete')) {
            abort(403);
        }

        // Security check: cannot delete if has job applications
        if ($alumnus->applications()->exists()) {
            return back()->with('error', 'Data alumni tidak dapat dihapus karena sudah memiliki riwayat lamaran kerja.');
        }

        $alumnus->delete();

        return redirect()->route('admin.bkk.alumni.index')
            ->with('success', 'Data alumni berhasil dihapus.');
    }
}
