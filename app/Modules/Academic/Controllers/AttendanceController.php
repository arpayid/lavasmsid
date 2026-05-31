<?php

namespace App\Modules\Academic\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\{Attendance, Classroom};
use App\Modules\Academic\Services\AttendanceService;
use App\Modules\Student\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(protected AttendanceService $attendanceService) {}

    public function index(Request $request): View
    {
        $query = Attendance::with(['student', 'classroom']);

        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }
        if ($request->filled('date')) {
            $query->where('attendance_date', $request->date);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderByDesc('attendance_date')->paginate(20);
        $classrooms = Classroom::orderBy('name')->get();

        return view('modules.academic.attendances.index', [
            'attendances' => $attendances,
            'classrooms' => $classrooms,
            'statuses' => [
                Attendance::STATUS_PRESENT => 'Hadir',
                Attendance::STATUS_SICK => 'Sakit',
                Attendance::STATUS_PERMISSION => 'Izin',
                Attendance::STATUS_ABSENT => 'Alpha',
                Attendance::STATUS_LATE => 'Terlambat',
            ],
        ]);
    }

    public function create(): View
    {
        $classrooms = Classroom::orderBy('name')->get();

        return view('modules.academic.attendances.create', [
            'classrooms' => $classrooms,
            'statuses' => [
                Attendance::STATUS_PRESENT => 'Hadir',
                Attendance::STATUS_SICK => 'Sakit',
                Attendance::STATUS_PERMISSION => 'Izin',
                Attendance::STATUS_ABSENT => 'Alpha',
                Attendance::STATUS_LATE => 'Terlambat',
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'attendance_date' => ['required', 'date'],
            'records' => ['required', 'array'],
            'records.*.status' => ['required', 'in:present,sick,permission,absent,late'],
            'records.*.note' => ['nullable', 'string'],
        ]);

        $this->attendanceService->submitBulk(
            $validated['classroom_id'],
            $validated['attendance_date'],
            $validated['records']
        );

        return redirect()->route('admin.attendances.index')
            ->with('success', 'Absensi berhasil disimpan.');
    }

    public function recap(Request $request): View
    {
        $studentId = $request->input('student_id');
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $recap = null;
        $student = null;

        if ($studentId) {
            $student = Student::findOrFail($studentId);
            $recap = $this->attendanceService->getMonthlyRecap($studentId, $month, $year);
        }

        $students = Student::with('classroom')->orderBy('name')->get();

        return view('modules.academic.attendances.recap', [
            'recap' => $recap,
            'student' => $student,
            'students' => $students,
            'month' => $month,
            'year' => $year,
        ]);
    }

    public function destroy(int $id): RedirectResponse
    {
        Attendance::findOrFail($id)->delete();

        return redirect()->route('admin.attendances.index')
            ->with('success', 'Data absensi berhasil dihapus.');
    }
}
