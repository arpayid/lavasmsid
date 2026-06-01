<?php

namespace App\Modules\Academic\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Attendance;
use App\Modules\Academic\Models\Classroom;
use App\Modules\Academic\Models\Student;
use App\Modules\Academic\Requests\BulkAttendanceRequest;
use App\Modules\Academic\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(protected AttendanceService $service) {}

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

        return view('modules.academic.attendances.index', [
            'attendances' => $attendances,
            'classrooms' => Classroom::orderBy('name')->get(),
            'statuses' => ['present' => 'Hadir', 'sick' => 'Sakit', 'permission' => 'Izin', 'absent' => 'Alpha', 'late' => 'Terlambat'],
        ]);
    }

    public function create(): View
    {
        return view('modules.academic.attendances.create', [
            'classrooms' => Classroom::orderBy('name')->get(),
        ]);
    }

    public function store(BulkAttendanceRequest $request): RedirectResponse
    {
        $this->service->submitBulk($request->validated());

        return redirect()->route('admin.attendances.index')->with('success', 'Absensi berhasil disimpan.');
    }

    public function recap(Request $request): View
    {
        $query = Attendance::with('student');
        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }
        if ($request->filled('month')) {
            $query->whereMonth('attendance_date', $request->month);
        }
        $attendances = $query->get();
        $classrooms = Classroom::orderBy('name')->get();

        // Group by student and count statuses
        $summary = $attendances->groupBy('student_id')->map(function ($records) {
            return [
                'student_name' => $records->first()->student->name ?? '',
                'present' => $records->where('status', 'present')->count(),
                'sick' => $records->where('status', 'sick')->count(),
                'permission' => $records->where('status', 'permission')->count(),
                'absent' => $records->where('status', 'absent')->count(),
                'late' => $records->where('status', 'late')->count(),
            ];
        });

        return view('modules.academic.attendances.recap', compact('summary', 'classrooms'));
    }

    public function destroy(Attendance $attendance): RedirectResponse
    {
        $attendance->delete();

        return redirect()->route('admin.attendances.index')->with('success', 'Absensi berhasil dihapus.');
    }
}
