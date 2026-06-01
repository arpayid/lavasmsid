<?php

namespace App\Modules\Academic\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Attendance;
use App\Modules\Academic\Models\Grade;
use App\Modules\Academic\Models\Semester;
use App\Modules\Student\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportCardController extends Controller
{
    public function index(Request $request): View
    {
        $students = Student::with(['classroom'])->orderBy('name')->get();
        $semesters = Semester::with('academicYear')->orderByDesc('id')->get();

        return view('modules.academic.report-cards.index', compact('students', 'semesters'));
    }

    public function show(Request $request): View
    {
        $request->validate(['student_id' => 'required|exists:students,id', 'semester_id' => 'required|exists:semesters,id']);
        $student = Student::with(['classroom', 'department'])->findOrFail($request->student_id);
        $grades = Grade::where('student_id', $request->student_id)->where('semester_id', $request->semester_id)->with('subject')->get();
        $attendances = Attendance::where('student_id', $request->student_id)->get();
        $attendanceSummary = [
            'present' => $attendances->where('status', 'present')->count(),
            'sick' => $attendances->where('status', 'sick')->count(),
            'permission' => $attendances->where('status', 'permission')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'late' => $attendances->where('status', 'late')->count(),
        ];

        return view('modules.academic.report-cards.show', compact('student', 'grades', 'attendanceSummary'));
    }

    public function pdf(Request $request)
    {
        // TODO: Implement DomPDF export
        return $this->show($request);
    }
}
