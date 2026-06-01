<?php

namespace App\Modules\Academic\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\AcademicYear;
use App\Modules\Academic\Models\Semester;
use App\Modules\Academic\Requests\StoreSemesterRequest;
use App\Modules\Academic\Requests\UpdateSemesterRequest;
use App\Modules\Academic\Services\SemesterService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SemesterController extends Controller
{
    public function __construct(protected SemesterService $service) {}

    public function index(): View
    {
        $semesters = Semester::with('academicYear')->orderByDesc('id')->paginate(15);

        return view('modules.academic.semesters.index', compact('semesters'));
    }

    public function create(): View
    {
        $academicYears = AcademicYear::orderByDesc('start_date')->get();

        return view('modules.academic.semesters.create', compact('academicYears'));
    }

    public function store(StoreSemesterRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.semesters.index')
            ->with('success', 'Semester berhasil ditambahkan.');
    }

    public function edit(Semester $semester): View
    {
        $academicYears = AcademicYear::orderByDesc('start_date')->get();

        return view('modules.academic.semesters.edit', compact('semester', 'academicYears'));
    }

    public function update(UpdateSemesterRequest $request, Semester $semester): RedirectResponse
    {
        $this->service->update($semester, $request->validated());

        return redirect()->route('admin.semesters.index')
            ->with('success', 'Semester berhasil diperbarui.');
    }

    public function destroy(Semester $semester): RedirectResponse
    {
        $this->service->delete($semester);

        return redirect()->route('admin.semesters.index')
            ->with('success', 'Semester berhasil dihapus.');
    }
}
