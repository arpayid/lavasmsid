<?php

namespace App\Modules\Academic\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Subject;
use App\Modules\Academic\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Subject::with('department');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $subjects = $query->orderBy('name')->paginate(15);

        return view('modules.academic.subjects.index', [
            'subjects' => $subjects,
        ]);
    }

    public function create(): View
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('modules.academic.subjects.create', [
            'departments' => $departments,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'department_id' => ['nullable', 'exists:departments,id'],
            'code' => ['required', 'string', 'unique:subjects,code'],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'in:general,productive,vocational'],
        ]);

        Subject::create($validated);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(Subject $subject): View
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('modules.academic.subjects.edit', [
            'subject' => $subject,
            'departments' => $departments,
        ]);
    }

    public function update(Request $request, Subject $subject): RedirectResponse
    {
        $validated = $request->validate([
            'department_id' => ['nullable', 'exists:departments,id'],
            'code' => ['required', 'string', 'unique:subjects,code,' . $subject->id],
            'name' => ['required', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'in:general,productive,vocational'],
        ]);

        $subject->update($validated);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}