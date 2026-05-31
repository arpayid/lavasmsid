<?php

namespace App\Modules\Academic\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Competency;
use App\Modules\Academic\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompetencyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Competency::with('department');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $competencies = $query->orderBy('name')->paginate(15);

        return view('modules.academic.competencies.index', [
            'competencies' => $competencies,
        ]);
    }

    public function create(): View
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('modules.academic.competencies.create', [
            'departments' => $departments,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'code' => ['required', 'string', 'unique:competencies,code'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        Competency::create($validated);

        return redirect()->route('admin.competencies.index')
            ->with('success', 'Kompetensi berhasil ditambahkan.');
    }

    public function edit(Competency $competency): View
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('modules.academic.competencies.edit', [
            'competency' => $competency,
            'departments' => $departments,
        ]);
    }

    public function update(Request $request, Competency $competency): RedirectResponse
    {
        $validated = $request->validate([
            'department_id' => ['required', 'exists:departments,id'],
            'code' => ['required', 'string', 'unique:competencies,code,' . $competency->id],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $competency->update($validated);

        return redirect()->route('admin.competencies.index')
            ->with('success', 'Kompetensi berhasil diperbarui.');
    }

    public function destroy(Competency $competency): RedirectResponse
    {
        $competency->delete();

        return redirect()->route('admin.competencies.index')
            ->with('success', 'Kompetensi berhasil dihapus.');
    }
}