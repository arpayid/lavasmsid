<?php

namespace App\Modules\Academic\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Competency;
use App\Modules\Academic\Models\Department;
use App\Modules\Academic\Requests\StoreCompetencyRequest;
use App\Modules\Academic\Requests\UpdateCompetencyRequest;
use App\Modules\Academic\Services\CompetencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompetencyController extends Controller
{
    public function __construct(protected CompetencyService $service) {}

    public function index(Request $request): View
    {
        $query = Competency::with('department');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $competencies = $query->orderBy('name')->paginate(15);

        return view('modules.academic.competencies.index', compact('competencies'));
    }

    public function create(): View
    {
        $departments = Department::orderBy('name')->get();

        return view('modules.academic.competencies.create', compact('departments'));
    }

    public function store(StoreCompetencyRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.competencies.index')
            ->with('success', 'Kompetensi berhasil ditambahkan.');
    }

    public function edit(Competency $competency): View
    {
        $departments = Department::orderBy('name')->get();

        return view('modules.academic.competencies.edit', compact('competency', 'departments'));
    }

    public function update(UpdateCompetencyRequest $request, Competency $competency): RedirectResponse
    {
        $this->service->update($competency, $request->validated());

        return redirect()->route('admin.competencies.index')
            ->with('success', 'Kompetensi berhasil diperbarui.');
    }

    public function destroy(Competency $competency): RedirectResponse
    {
        $this->service->delete($competency);

        return redirect()->route('admin.competencies.index')
            ->with('success', 'Kompetensi berhasil dihapus.');
    }
}
