<?php

namespace App\Modules\Teacher\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Subject;
use App\Modules\Teacher\Models\Teacher;
use App\Modules\Teacher\Requests\StoreTeacherRequest;
use App\Modules\Teacher\Requests\UpdateTeacherRequest;
use App\Modules\Teacher\Services\TeacherService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeacherController extends Controller
{
    public function __construct(protected TeacherService $service) {}

    public function index(Request $request): View
    {
        $query = Teacher::query();
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('nip', 'like', '%'.$request->search.'%')
                    ->orWhere('nuptk', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $teachers = $query->orderBy('name')->paginate(15);

        return view('modules.teacher.index', compact('teachers'));
    }

    public function create(): View
    {
        return view('modules.teacher.create');
    }

    public function store(StoreTeacherRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function show(Teacher $teacher): View
    {
        $teacher->load(['subjects']);

        return view('modules.teacher.show', compact('teacher'));
    }

    public function edit(Teacher $teacher): View
    {
        return view('modules.teacher.edit', [
            'teacher' => $teacher,
            'subjects' => Subject::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateTeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        $this->service->update($teacher, $request->validated());

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher): RedirectResponse
    {
        $this->service->delete($teacher);

        return redirect()->route('admin.teachers.index')->with('success', 'Guru berhasil dihapus.');
    }
}
