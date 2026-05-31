<?php

namespace App\Modules\Academic\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Grade;
use App\Modules\Academic\Models\Semester;
use App\Modules\Academic\Models\Subject;
use App\Modules\Academic\Services\GradeService;
use App\Modules\Student\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function __construct(protected GradeService $gradeService) {}

    public function index(Request $request): View
    {
        $query = Grade::with(['student', 'subject', 'semester']);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $grades = $query->orderByDesc('updated_at')->paginate(20);

        return view('modules.academic.grades.index', [
            'grades' => $grades,
            'students' => Student::orderBy('name')->get(),
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

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'semester_id' => ['required', 'exists:semesters,id'],
            'assignment_score' => ['nullable', 'numeric', 'between:0,100'],
            'midterm_score' => ['nullable', 'numeric', 'between:0,100'],
            'final_score' => ['nullable', 'numeric', 'between:0,100'],
            'practice_score' => ['nullable', 'numeric', 'between:0,100'],
        ]);

        $this->gradeService->saveGrade($validated);

        return redirect()->route('admin.grades.index')
            ->with('success', 'Nilai berhasil disimpan.');
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

    public function update(Request $request, Grade $grade): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'semester_id' => ['required', 'exists:semesters,id'],
            'assignment_score' => ['nullable', 'numeric', 'between:0,100'],
            'midterm_score' => ['nullable', 'numeric', 'between:0,100'],
            'final_score' => ['nullable', 'numeric', 'between:0,100'],
            'practice_score' => ['nullable', 'numeric', 'between:0,100'],
        ]);

        $this->gradeService->saveGrade($validated);

        return redirect()->route('admin.grades.index')
            ->with('success', 'Nilai berhasil diperbarui.');
    }

    public function destroy(Grade $grade): RedirectResponse
    {
        $grade->delete();

        return redirect()->route('admin.grades.index')
            ->with('success', 'Nilai berhasil dihapus.');
    }
}
