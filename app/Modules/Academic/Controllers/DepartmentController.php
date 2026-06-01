<?php

namespace App\Modules\Academic\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Department;
use App\Modules\Academic\Requests\StoreDepartmentRequest;
use App\Modules\Academic\Requests\UpdateDepartmentRequest;
use App\Modules\Academic\Services\DepartmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DepartmentController extends Controller
{
    public function __construct(protected DepartmentService $service) {}

    public function index(Request $request): View
    {
        $query = Department::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $departments = $query->orderBy('name')->paginate(15);

        return view('modules.academic.departments.index', compact('departments'));
    }

    public function create(): View
    {
        return view('modules.academic.departments.create');
    }

    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.departments.index')
            ->with('success', 'Jurusan berhasil ditambahkan.');
    }

    public function show(Department $department): View
    {
        $department->loadCount(['competencies', 'classrooms', 'subjects']);

        return view('modules.academic.departments.show', compact('department'));
    }

    public function edit(Department $department): View
    {
        return view('modules.academic.departments.edit', compact('department'));
    }

    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $this->service->update($department, $request->validated());

        return redirect()->route('admin.departments.index')
            ->with('success', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(Department $department): RedirectResponse
    {
        $this->service->delete($department);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Jurusan berhasil dihapus.');
    }
}
