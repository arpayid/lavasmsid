<?php

namespace App\Modules\Academic\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Classroom;
use App\Modules\Academic\Models\Schedule;
use App\Modules\Academic\Models\Subject;
use App\Modules\Academic\Requests\StoreScheduleRequest;
use App\Modules\Academic\Requests\UpdateScheduleRequest;
use App\Modules\Academic\Services\ScheduleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function __construct(protected ScheduleService $service) {}

    public function index(Request $request): View
    {
        $query = Schedule::with(['classroom', 'subject', 'teacher', 'semester']);
        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('day')) {
            $query->where('day', $request->day);
        }
        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }
        $schedules = $query->orderBy('day')->orderBy('start_time')->paginate(20);

        return view('modules.academic.schedules.index', [
            'schedules' => $schedules,
            'classrooms' => Classroom::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'],
        ]);
    }

    public function create(): View
    {
        return view('modules.academic.schedules.create', [
            'classrooms' => Classroom::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }

    public function store(StoreScheduleRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($conflicts = $this->service->detectConflicts($data)) {
            return back()->withErrors(['conflict' => implode(', ', $conflicts)])->withInput();
        }
        $this->service->create($data);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule): View
    {
        return view('modules.academic.schedules.edit', [
            'schedule' => $schedule,
            'classrooms' => Classroom::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateScheduleRequest $request, Schedule $schedule): RedirectResponse
    {
        $data = $request->validated();
        if ($conflicts = $this->service->detectConflicts($data, $schedule->id)) {
            return back()->withErrors(['conflict' => implode(', ', $conflicts)])->withInput();
        }
        $this->service->update($schedule, $data);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule): RedirectResponse
    {
        $this->service->delete($schedule);

        return redirect()->route('admin.schedules.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
