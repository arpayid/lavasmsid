<?php

namespace App\Modules\Academic\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\AcademicYear;
use App\Modules\Academic\Requests\StoreAcademicYearRequest;
use App\Modules\Academic\Requests\UpdateAcademicYearRequest;
use App\Modules\Academic\Services\AcademicYearService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AcademicYearController extends Controller
{
    public function __construct(protected AcademicYearService $service) {}

    public function index(Request $request): View
    {
        $query = AcademicYear::withCount('semesters');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $academicYears = $query->orderByDesc('start_date')->paginate(15);

        return view('modules.academic.academic-years.index', compact('academicYears'));
    }

    public function create(): View
    {
        return view('modules.academic.academic-years.create');
    }

    public function store(StoreAcademicYearRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function show(AcademicYear $academicYear): View
    {
        $academicYear->load('semesters');

        return view('modules.academic.academic-years.show', compact('academicYear'));
    }

    public function edit(AcademicYear $academicYear): View
    {
        return view('modules.academic.academic-years.edit', compact('academicYear'));
    }

    public function update(UpdateAcademicYearRequest $request, AcademicYear $academicYear): RedirectResponse
    {
        $this->service->update($academicYear, $request->validated());

        return redirect()->route('admin.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(AcademicYear $academicYear): RedirectResponse
    {
        $this->service->delete($academicYear);

        return redirect()->route('admin.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}
