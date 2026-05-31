<?php

namespace App\Modules\Academic\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Classroom;
use App\Modules\Academic\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassroomController extends Controller
{
    public function index(Request $request): View
    {
        $query = Classroom::with('department');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $classrooms = $query->orderBy('name')->paginate(15);

        return view('modules.academic.classrooms.index', [
            'classrooms' => $classrooms,
        ]);
    }

    public function create(): View
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('modules.academic.classrooms.create', [
            'departments' => $departments,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'department_id' => ['nullable', 'exists:departments,id'],
            'name' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:50'],
            'room' => ['nullable', 'string', 'max:100'],
        ]);

        Classroom::create($validated);

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Classroom $classroom): View
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('modules.academic.classrooms.edit', [
            'classroom' => $classroom,
            'departments' => $departments,
        ]);
    }

    public function update(Request $request, Classroom $classroom): RedirectResponse
    {
        $validated = $request->validate([
            'department_id' => ['nullable', 'exists:departments,id'],
            'name' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:50'],
            'room' => ['nullable', 'string', 'max:100'],
        ]);

        $classroom->update($validated);

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Classroom $classroom): RedirectResponse
    {
        $classroom->delete();

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}