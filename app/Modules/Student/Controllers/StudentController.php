<?php

namespace App\Modules\Student\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Classroom;
use App\Modules\Academic\Models\Department;
use App\Modules\Student\Models\Student;
use App\Modules\Student\Requests\StoreStudentRequest;
use App\Modules\Student\Requests\UpdateStudentRequest;
use App\Modules\Student\Services\StudentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function __construct(protected StudentService $service) {}

    public function index(Request $request): View
    {
        $query = Student::with(['department', 'classroom']);
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('nis', 'like', '%'.$request->search.'%')
                    ->orWhere('nisn', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->orderBy('name')->paginate(15);

        return view('modules.student.index', compact('students'));
    }

    public function create(): View
    {
        return view('modules.student.create', [
            'departments' => Department::orderBy('name')->get(),
            'classrooms' => Classroom::orderBy('name')->get(),
        ]);
    }

    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function show(Student $student): View
    {
        $student->load(['department', 'classroom']);

        return view('modules.student.show', compact('student'));
    }

    public function edit(Student $student): View
    {
        return view('modules.student.edit', [
            'student' => $student,
            'departments' => Department::orderBy('name')->get(),
            'classrooms' => Classroom::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        $this->service->update($student, $request->validated());

        return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil diperbarui.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $this->service->delete($student);

        return redirect()->route('admin.students.index')->with('success', 'Siswa berhasil dihapus.');
    }
}
