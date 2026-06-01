<?php

namespace App\Modules\Academic\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Grade;
use App\Modules\Academic\Models\Semester;
use App\Modules\Academic\Models\Student;
use App\Modules\Academic\Models\Subject;
use App\Modules\Academic\Requests\BulkGradeRequest;
use App\Modules\Academic\Requests\StoreGradeRequest;
use App\Modules\Academic\Requests\UpdateGradeRequest;
use App\Modules\Academic\Services\GradeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function __construct(protected GradeService $service) {}

    public function index(Request $request): View
    {
        $query = Grade::with(['student', 'subject', 'semester']);
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }
        $grades = $query->orderByDesc('updated_at')->paginate(20);

        return view('modules.academic.grades.index', [
            'grades' => $grades,
            'subjects' => Subject::orderBy('name')->get(),
            'semesters' => Semester::orderByDesc('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('modules.academic.grades.create', [
            'students' => Student::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'semesters' => Semester::orderByDesc('id')->get(),
        ]);
    }

    public function store(StoreGradeRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.grades.index')->with('success', 'Nilai berhasil ditambahkan.');
    }

    public function bulk(Request $request): View
    {
        return view('modules.academic.grades.bulk', [
            'students' => Student::with('classroom')->orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'semesters' => Semester::orderByDesc('id')->get(),
        ]);
    }

    public function bulkStore(BulkGradeRequest $request): RedirectResponse
    {
        $this->service->bulkCreate($request->validated());

        return redirect()->route('admin.grades.index')->with('success', 'Nilai massal berhasil disimpan.');
    }

    public function edit(Grade $grade): View
    {
        return view('modules.academic.grades.edit', [
            'grade' => $grade,
            'students' => Student::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'semesters' => Semester::orderByDesc('id')->get(),
        ]);
    }

    public function update(UpdateGradeRequest $request, Grade $grade): RedirectResponse
    {
        $this->service->update($grade, $request->validated());

        return redirect()->route('admin.grades.index')->with('success', 'Nilai berhasil diperbarui.');
    }

    public function destroy(Grade $grade): RedirectResponse
    {
        $grade->delete();

        return redirect()->route('admin.grades.index')->with('success', 'Nilai berhasil dihapus.');
    }
}
