<?php

namespace App\Modules\BKK\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Department;
use App\Modules\Alumni\Models\Alumni;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AlumniController extends Controller
{
    public function index(Request $request): View
    {
        $query = Alumni::with('department')->orderByDesc('graduation_year');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        $alumni = $query->paginate(20);
        $departments = Department::orderBy('name')->get();

        // Dashboard stats
        $stats = [
            'total' => Alumni::count(),
            'working' => Alumni::where('status', 'working')->count(),
            'studying' => Alumni::where('status', 'studying')->count(),
            'entrepreneur' => Alumni::where('status', 'entrepreneur')->count(),
            'unemployed' => Alumni::where('status', 'unemployed')->count(),
        ];

        return view('modules.bkk.alumni.index', compact('alumni', 'departments', 'stats'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('name')->get();

        return view('modules.bkk.alumni.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nis' => ['nullable', 'string', 'max:50'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'graduation_year' => ['required', 'integer', 'min:2000', 'max:'.date('Y')],
            'status' => ['required', 'in:unemployed,working,studying,entrepreneur'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'salary_range' => ['nullable', 'string', 'max:50'],
            'institution_name' => ['nullable', 'string', 'max:255'],
            'study_program' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        Alumni::create($validated);

        return redirect()->route('admin.bkk.alumni.index')
            ->with('success', 'Data alumni berhasil ditambahkan.');
    }

    public function edit(Alumni $alumni): View
    {
        $departments = Department::orderBy('name')->get();

        return view('modules.bkk.alumni.edit', compact('alumni', 'departments'));
    }

    public function update(Request $request, Alumni $alumni): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nis' => ['nullable', 'string', 'max:50'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'graduation_year' => ['required', 'integer', 'min:2000', 'max:'.date('Y')],
            'status' => ['required', 'in:unemployed,working,studying,entrepreneur'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'job_title' => ['nullable', 'string', 'max:255'],
            'salary_range' => ['nullable', 'string', 'max:50'],
            'institution_name' => ['nullable', 'string', 'max:255'],
            'study_program' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
        ]);

        $alumni->update($validated);

        return redirect()->route('admin.bkk.alumni.index')
            ->with('success', 'Data alumni berhasil diperbarui.');
    }

    public function destroy(Alumni $alumni): RedirectResponse
    {
        $alumni->delete();

        return redirect()->route('admin.bkk.alumni.index')
            ->with('success', 'Data alumni berhasil dihapus.');
    }
}
