<?php

namespace App\Modules\Academic\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\{Schedule, Classroom, Subject};
use App\Modules\Academic\Services\ScheduleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function __construct(protected ScheduleService $scheduleService) {}

    public function index(Request $request): View
    {
        $query = Schedule::with(['classroom.department', 'subject']);

        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }
        if ($request->filled('day')) {
            $query->where('day', $request->day);
        }

        $schedules = $query->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('start_time')
            ->paginate(20);

        $classrooms = Classroom::with('department')->orderBy('name')->get();
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        return view('modules.academic.schedules.index', [
            'schedules' => $schedules,
            'classrooms' => $classrooms,
            'days' => $days,
        ]);
    }

    public function create(): View
    {
        return view('modules.academic.schedules.create', [
            'classrooms' => Classroom::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'days' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'day' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'room' => ['nullable', 'string', 'max:100'],
        ]);

        try {
            $this->scheduleService->create($validated);

            return redirect()->route('admin.schedules.index')
                ->with('success', 'Jadwal berhasil ditambahkan.');
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['conflict' => $e->getMessage()])->withInput();
        }
    }

    public function edit(Schedule $schedule): View
    {
        return view('modules.academic.schedules.edit', [
            'schedule' => $schedule,
            'classrooms' => Classroom::orderBy('name')->get(),
            'subjects' => Subject::orderBy('name')->get(),
            'days' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
        ]);
    }

    public function update(Request $request, Schedule $schedule): RedirectResponse
    {
        $validated = $request->validate([
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => ['nullable', 'exists:users,id'],
            'day' => ['required', 'in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'room' => ['nullable', 'string', 'max:100'],
        ]);

        try {
            $this->scheduleService->update($schedule, $validated);

            return redirect()->route('admin.schedules.index')
                ->with('success', 'Jadwal berhasil diperbarui.');
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['conflict' => $e->getMessage()])->withInput();
        }
    }

    public function destroy(Schedule $schedule): RedirectResponse
    {
        $schedule->delete();

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}
