<?php

namespace App\Modules\Academic\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Department;
use App\Modules\Academic\Models\Subject;
use App\Modules\Academic\Requests\StoreSubjectRequest;
use App\Modules\Academic\Requests\UpdateSubjectRequest;
use App\Modules\Academic\Services\SubjectService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function __construct(protected SubjectService $service) {}

    public function index(Request $request): View
    {
        $query = Subject::with('department');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $subjects = $query->orderBy('name')->paginate(15);

        return view('modules.academic.subjects.index', compact('subjects'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('name')->get();

        return view('modules.academic.subjects.create', compact('departments'));
    }

    public function store(StoreSubjectRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(Subject $subject): View
    {
        $departments = Department::orderBy('name')->get();

        return view('modules.academic.subjects.edit', compact('subject', 'departments'));
    }

    public function update(UpdateSubjectRequest $request, Subject $subject): RedirectResponse
    {
        $this->service->update($subject, $request->validated());

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $this->service->delete($subject);

        return redirect()->route('admin.subjects.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
