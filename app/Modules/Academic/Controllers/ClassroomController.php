<?php

namespace App\Modules\Academic\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Classroom;
use App\Modules\Academic\Models\Department;
use App\Modules\Academic\Requests\StoreClassroomRequest;
use App\Modules\Academic\Requests\UpdateClassroomRequest;
use App\Modules\Academic\Services\ClassroomService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClassroomController extends Controller
{
    public function __construct(protected ClassroomService $service) {}

    public function index(Request $request): View
    {
        $query = Classroom::with('department');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $classrooms = $query->orderBy('name')->paginate(15);

        return view('modules.academic.classrooms.index', compact('classrooms'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('name')->get();

        return view('modules.academic.classrooms.create', compact('departments'));
    }

    public function store(StoreClassroomRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Classroom $classroom): View
    {
        $departments = Department::orderBy('name')->get();

        return view('modules.academic.classrooms.edit', compact('classroom', 'departments'));
    }

    public function update(UpdateClassroomRequest $request, Classroom $classroom): RedirectResponse
    {
        $this->service->update($classroom, $request->validated());

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Classroom $classroom): RedirectResponse
    {
        $this->service->delete($classroom);

        return redirect()->route('admin.classrooms.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }
}
