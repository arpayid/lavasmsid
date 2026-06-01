<?php

namespace App\Modules\Academic\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Semester;
use App\Modules\Academic\Services\ReportCardService;
use App\Modules\Student\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportCardController extends Controller
{
    public function __construct(protected ReportCardService $service) {}

    public function index(Request $request): View
    {
        $students = Student::with(['classroom'])->orderBy('name')->get();
        $semesters = Semester::with('academicYear')->orderByDesc('id')->get();

        return view('modules.academic.report-cards.index', compact('students', 'semesters'));
    }

    public function show(Request $request): View
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'semester_id' => ['required', 'exists:semesters,id'],
        ]);

        $data = $this->service->build((int) $validated['student_id'], (int) $validated['semester_id']);

        return view('modules.academic.report-cards.show', $data);
    }

    public function pdf(Request $request): View
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'semester_id' => ['required', 'exists:semesters,id'],
        ]);

        $data = $this->service->build((int) $validated['student_id'], (int) $validated['semester_id']);

        return view('modules.academic.report-cards.show', $data);
    }
}
