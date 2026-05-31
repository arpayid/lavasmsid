<?php

namespace App\Modules\Report\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Attendance;
use App\Modules\Academic\Models\Classroom;
use App\Modules\Academic\Models\Department;
use App\Modules\Academic\Models\Grade;
use App\Modules\Academic\Models\Semester;
use App\Modules\Academic\Models\Subject;
use App\Modules\Alumni\Models\Alumni;
use App\Modules\Finance\Models\Invoice;
use App\Modules\Finance\Models\PaymentCategory;
use App\Modules\Internship\Models\Internship;
use App\Modules\PPDB\Models\PpdbRegistration;
use App\Modules\Student\Models\Student;
use App\Modules\Teacher\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $reportTypes = [
            ['key' => 'students', 'name' => 'Laporan Siswa', 'icon' => '👨‍🎓'],
            ['key' => 'teachers', 'name' => 'Laporan Guru', 'icon' => '👨‍🏫'],
            ['key' => 'classrooms', 'name' => 'Laporan Kelas', 'icon' => '🏫'],
            ['key' => 'departments', 'name' => 'Laporan Jurusan', 'icon' => '📚'],
            ['key' => 'attendance', 'name' => 'Laporan Absensi', 'icon' => '📋'],
            ['key' => 'grades', 'name' => 'Laporan Nilai', 'icon' => '📝'],
            ['key' => 'finance', 'name' => 'Laporan Keuangan', 'icon' => '💰'],
            ['key' => 'ppdb', 'name' => 'Laporan PPDB', 'icon' => '📩'],
            ['key' => 'internship', 'name' => 'Laporan PKL', 'icon' => '🏭'],
            ['key' => 'alumni', 'name' => 'Laporan Alumni', 'icon' => '🎓'],
        ];

        return view('modules.reports.index', compact('reportTypes'));
    }

    public function students(Request $request): View
    {
        $query = Student::with(['department', 'classroom'])->orderBy('name');

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $students = $query->get();
        $departments = Department::orderBy('name')->get();
        $classrooms = Classroom::orderBy('name')->get();

        return view('modules.reports.students', compact('students', 'departments', 'classrooms'));
    }

    public function classrooms(Request $request): View
    {
        $classrooms = Classroom::with(['department'])->orderBy('name')->get();

        return view('modules.reports.classrooms', compact('classrooms'));
    }

    public function departments(Request $request): View
    {
        $departments = Department::withCount(['students', 'classrooms'])->orderBy('name')->get();

        return view('modules.reports.departments', compact('departments'));
    }

    public function teachers(Request $request): View
    {
        $query = Teacher::orderBy('name');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $teachers = $query->get();

        return view('modules.reports.teachers', compact('teachers'));
    }

    public function attendance(Request $request): View
    {
        $query = Attendance::with(['student', 'classroom']);

        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('attendance_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('attendance_date', '<=', $request->date_to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderByDesc('attendance_date')->get();

        // Summary stats
        $summary = [
            'present' => Attendance::where('status', 'present')->count(),
            'sick' => Attendance::where('status', 'sick')->count(),
            'permission' => Attendance::where('status', 'permission')->count(),
            'absent' => Attendance::where('status', 'absent')->count(),
            'late' => Attendance::where('status', 'late')->count(),
        ];

        $classrooms = Classroom::orderBy('name')->get();

        return view('modules.reports.attendance', compact('attendances', 'classrooms', 'summary'));
    }

    public function grades(Request $request): View
    {
        $query = Grade::with(['student', 'subject', 'semester']);

        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }

        $grades = $query->get();

        $semesters = Semester::with('academicYear')->orderByDesc('id')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('modules.reports.grades', compact('grades', 'semesters', 'subjects'));
    }

    public function finance(Request $request): View
    {
        $query = Invoice::with(['student', 'paymentCategory']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category_id')) {
            $query->where('payment_category_id', $request->category_id);
        }

        $invoices = $query->get();

        $summary = [
            'total_invoices' => Invoice::count(),
            'total_amount' => Invoice::sum('amount'),
            'total_paid' => Invoice::sum('paid_amount'),
            'total_unpaid' => Invoice::where('status', 'unpaid')->sum('amount') - Invoice::where('status', 'unpaid')->sum('paid_amount'),
        ];

        $categories = PaymentCategory::orderBy('name')->get();

        return view('modules.reports.finance', compact('invoices', 'categories', 'summary'));
    }

    public function ppdb(Request $request): View
    {
        $query = PpdbRegistration::with('department')->orderByDesc('created_at');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $registrations = $query->get();

        $summary = [
            'total' => PpdbRegistration::count(),
            'submitted' => PpdbRegistration::where('status', 'submitted')->count(),
            'verified' => PpdbRegistration::where('status', 'verified')->count(),
            'accepted' => PpdbRegistration::where('status', 'accepted')->count(),
            'rejected' => PpdbRegistration::where('status', 'rejected')->count(),
        ];

        return view('modules.reports.ppdb', compact('registrations', 'summary'));
    }

    public function internship(Request $request): View
    {
        $query = Internship::with(['student', 'industryPartner'])->orderByDesc('start_date');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $internships = $query->get();

        $summary = [
            'total' => Internship::count(),
            'planned' => Internship::where('status', 'planned')->count(),
            'ongoing' => Internship::where('status', 'ongoing')->count(),
            'completed' => Internship::where('status', 'completed')->count(),
        ];

        return view('modules.reports.internship', compact('internships', 'summary'));
    }

    public function alumni(Request $request): View
    {
        $query = Alumni::with('department')->orderByDesc('graduation_year');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        $alumni = $query->get();

        $departments = Department::orderBy('name')->get();

        $summary = [
            'total' => Alumni::count(),
            'working' => Alumni::where('status', 'working')->count(),
            'studying' => Alumni::where('status', 'studying')->count(),
            'entrepreneur' => Alumni::where('status', 'entrepreneur')->count(),
        ];

        return view('modules.reports.alumni', compact('alumni', 'departments', 'summary'));
    }
}
