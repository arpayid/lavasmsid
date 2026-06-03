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
use App\Modules\Communication\Models\Announcement;
use App\Modules\Communication\Models\Message;
use App\Modules\Communication\Models\Notification;
use App\Modules\Finance\Models\Invoice;
use App\Modules\Finance\Models\PaymentCategory;
use App\Modules\Internship\Models\Internship;
use App\Modules\PPDB\Models\PpdbRegistration;
use App\Modules\Student\Models\Student;
use App\Modules\Teacher\Models\Teacher;
use App\Modules\Website\Models\Achievement;
use App\Modules\Website\Models\CmsPage;
use App\Modules\Website\Models\Event;
use App\Modules\Website\Models\Facility;
use App\Modules\Website\Models\News;
use App\Services\ReportCsvExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

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
            ['key' => 'website', 'name' => 'Laporan Website', 'icon' => '🌐'],
            ['key' => 'communication', 'name' => 'Laporan Komunikasi', 'icon' => '💬'],
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
        $classrooms = Classroom::with(['department', 'homeroomTeacher'])->orderBy('name')->get();

        return view('modules.reports.classrooms', compact('classrooms'));
    }

    public function exportClassrooms(): StreamedResponse
    {
        if (Gate::denies('report.view')) {
            abort(403);
        }

        $service = new ReportCsvExportService;
        $rows = Classroom::with(['department', 'homeroomTeacher'])->orderBy('name')->cursor()->map(fn ($c) => [
            $c->name,
            $c->grade ?? $c->level ?? '',
            $c->department->name ?? '-',
            $c->homeroomTeacher->name ?? '-',
            $c->students_count ?? $c->students()->count(),
            $c->academicYear?->name ?? '',
            $c->created_at->format('Y-m-d'),
        ]);

        return $service->download('classrooms-report.csv',
            ['Nama Kelas', 'Tingkat', 'Jurusan', 'Wali Kelas', 'Jumlah Siswa', 'Tahun Ajaran', 'Dibuat'],
            $rows);
    }

    public function departments(Request $request): View
    {
        $departments = Department::withCount(['students', 'classrooms'])->orderBy('name')->get();

        return view('modules.reports.departments', compact('departments'));
    }

    public function teachers(Request $request): View
    {
        $query = Teacher::withCount('subjects')->orderBy('name');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $teachers = $query->get();

        return view('modules.reports.teachers', compact('teachers'));
    }

    public function exportTeachers(): StreamedResponse
    {
        if (Gate::denies('report.view')) {
            abort(403);
        }

        $service = new ReportCsvExportService;
        $rows = Teacher::with(['department'])->withCount('subjects')->orderBy('name')->cursor()->map(fn ($t) => [
            $t->name,
            $t->nip ?? '',
            $t->email ?? '',
            $t->phone ?? '',
            $t->department->name ?? '-',
            $t->subjects_count,
            $t->status ?? '',
            $t->created_at->format('Y-m-d'),
        ]);

        return $service->download('teachers-report.csv',
            ['Nama', 'NIP', 'Email', 'Telepon', 'Jurusan', 'Jumlah Mapel', 'Status', 'Dibuat'],
            $rows);
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
            'total_unpaid' => (float) Invoice::where('status', 'unpaid')
                ->selectRaw('COALESCE(SUM(amount), 0) - COALESCE(SUM(paid_amount), 0) as total_unpaid')
                ->value('total_unpaid'),
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

    /**
     * Students CSV Export
     */
    public function exportStudents(Request $request): StreamedResponse
    {
        if (Gate::denies('report.view')) {
            abort(403);
        }

        $query = Student::with(['department', 'classroom'])->orderBy('name');

        if ($request->filled('classroom_id')) {
            $query->where('classroom_id', $request->classroom_id);
        }
        if ($request->filled('department_id')) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $service = new ReportCsvExportService;

        return $service->download(
            'students-report.csv',
            ['NIS', 'NISN', 'Nama', 'Jenis Kelamin', 'Kelas', 'Jurusan', 'Status', 'Tanggal Dibuat'],
            $query->cursor(),
            ['nis', 'nisn', 'name', 'gender', 'classroom.name', 'department.name', 'status', 'created_at']
        );
    }

    /**
     * Finance CSV Export
     */
    public function exportFinance(Request $request): StreamedResponse
    {
        if (Gate::denies('report.view')) {
            abort(403);
        }

        $query = Invoice::with(['student', 'paymentCategory']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category_id')) {
            $query->where('payment_category_id', $request->category_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $service = new ReportCsvExportService;

        $rows = $query->cursor()->map(function ($inv) {
            return [
                $inv->invoice_number,
                $inv->student->name ?? '-',
                $inv->paymentCategory->name ?? '-',
                $inv->amount,
                $inv->paid_amount,
                $inv->getRemainingAmount(),
                $inv->status,
                $inv->due_date?->format('Y-m-d') ?? '',
                $inv->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return $service->download(
            'finance-report.csv',
            ['No. Invoice', 'Siswa', 'Kategori', 'Jumlah Tagihan', 'Terbayar', 'Sisa Tagihan', 'Status', 'Jatuh Tempo', 'Tanggal Dibuat'],
            $rows
        );
    }

    /**
     * PPDB CSV Export
     */
    public function exportPpdb(Request $request): StreamedResponse
    {
        if (Gate::denies('report.view')) {
            abort(403);
        }

        $query = PpdbRegistration::with('department');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $service = new ReportCsvExportService;

        return $service->download(
            'ppdb-report.csv',
            ['No. Pendaftaran', 'Nama Pendaftar', 'Jenis Kelamin', 'Asal Sekolah', 'Pilihan Jurusan', 'Status', 'Tanggal Daftar'],
            $query->cursor(),
            ['registration_number', 'candidate_name', 'gender', 'previous_school', 'department.name', 'status', 'created_at']
        );
    }

    /**
     * Smoke test export to verify the CSV export service works.
     */
    public function exportSample(Request $request): StreamedResponse
    {
        if (Gate::denies('report.view')) {
            abort(403);
        }

        $rows = [
            ['John Doe', 'XII-RPL-1', 'Laki-laki', 'Aktif'],
            ['Jane Smith', 'XII-RPL-2', 'Perempuan', 'Aktif'],
        ];

        $service = new ReportCsvExportService;

        return $service->download(
            'sample_report.csv',
            ['Nama', 'Kelas', 'Jenis Kelamin', 'Status'],
            $rows
        );
    }

    /**
     * Attendance CSV Export
     */
    public function exportAttendance(Request $request): StreamedResponse
    {
        if (Gate::denies('report.view')) {
            abort(403);
        }

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

        $service = new ReportCsvExportService;

        return $service->download(
            'attendance-report.csv',
            ['Tanggal', 'Nama Siswa', 'NIS', 'Kelas', 'Status', 'Catatan', 'Dibuat'],
            $query->cursor()->map(fn ($a) => [
                $a->attendance_date?->format('Y-m-d') ?? '',
                $a->student->name ?? '-',
                $a->student?->nis ?? '-',
                $a->classroom?->name ?? '-',
                $a->status,
                $a->note ?? '',
                $a->created_at->format('Y-m-d'),
            ])
        );
    }

    /**
     * Grades CSV Export
     */
    public function exportGrades(Request $request): StreamedResponse
    {
        if (Gate::denies('report.view')) {
            abort(403);
        }

        $query = Grade::with(['student', 'subject', 'semester.academicYear']);

        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }

        $service = new ReportCsvExportService;

        return $service->download(
            'grades-report.csv',
            ['Nama Siswa', 'NIS', 'Kelas', 'Mata Pelajaran', 'Nilai Akhir', 'Huruf', 'Predikat', 'Semester', 'Tahun Ajaran', 'Dibuat'],
            $query->cursor()->map(fn ($g) => [
                $g->student->name ?? '-',
                $g->student?->nis ?? '-',
                $g->student?->classroom?->name ?? '-',
                $g->subject->name ?? '-',
                $g->final_score ?? $g->score ?? '',
                $g->grade_letter ?? '',
                $g->predicate ?? '',
                $g->semester?->name ?? '',
                $g->semester?->academicYear?->name ?? '',
                $g->created_at->format('Y-m-d'),
            ])
        );
    }

    /**
     * Internship CSV Export
     */
    public function exportInternships(Request $request): StreamedResponse
    {
        if (Gate::denies('report.view')) {
            abort(403);
        }

        $query = Internship::with(['student', 'industryPartner', 'user', 'score']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('start_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('end_date', '<=', $request->date_to);
        }

        $service = new ReportCsvExportService;

        return $service->download(
            'internships-report.csv',
            ['Nama Siswa', 'NIS', 'Mitra Industri', 'Pembimbing', 'Mulai', 'Selesai', 'Status', 'Nilai', 'Predikat'],
            $query->cursor()->map(fn ($i) => [
                $i->student->name ?? '-',
                $i->student?->nis ?? '-',
                $i->industryPartner->name ?? '-',
                $i->user?->name ?? '-',
                $i->start_date?->format('Y-m-d') ?? '',
                $i->end_date?->format('Y-m-d') ?? '',
                $i->status,
                $i->grade ?? $i->score?->final_score ?? '',
                $i->score?->predicate ?? '',
            ])
        );
    }

    /**
     * Alumni / BKK CSV Export
     */
    public function exportAlumni(Request $request): StreamedResponse
    {
        if (Gate::denies('report.view')) {
            abort(403);
        }

        $query = Alumni::with('department');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('graduation_year')) {
            $query->where('graduation_year', $request->graduation_year);
        }

        $service = new ReportCsvExportService;

        return $service->download(
            'alumni-report.csv',
            ['Nama Alumni', 'Tahun Lulus', 'Email', 'Telepon', 'Status Pekerjaan', 'Tempat Kerja', 'Jabatan', 'Institusi', 'Program Studi', 'Dibuat'],
            $query->cursor()->map(fn ($a) => [
                $a->name,
                $a->graduation_year,
                $a->email ?? '',
                $a->phone ?? '',
                $a->status,
                $a->company_name ?? '',
                $a->job_title ?? '',
                $a->institution_name ?? '',
                $a->study_program ?? '',
                $a->created_at->format('Y-m-d'),
            ])
        );
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

    // ===================== Website CMS Report =====================

    public function website(): View
    {
        if (Gate::denies('report.view')) {
            abort(403);
        }

        $stats = [
            'total_news' => Schema::hasTable('news') ? News::count() : 0,
            'published_news' => Schema::hasTable('news') ? News::where('is_published', true)->count() : 0,
            'draft_news' => Schema::hasTable('news') ? News::where('is_published', false)->count() : 0,
            'total_events' => Schema::hasTable('events') ? Event::count() : 0,
            'total_pages' => Schema::hasTable('cms_pages') ? CmsPage::count() : 0,
            'total_facilities' => Schema::hasTable('facilities') ? Facility::count() : 0,
            'total_achievements' => Schema::hasTable('achievements') ? Achievement::count() : 0,
        ];

        $latestNews = Schema::hasTable('news') ? News::latest()->take(5)->get() : collect();
        $latestEvents = Schema::hasTable('events') ? Event::latest()->take(5)->get() : collect();

        return view('modules.reports.website', compact('stats', 'latestNews', 'latestEvents'));
    }

    public function exportWebsite(): StreamedResponse
    {
        if (Gate::denies('report.view')) {
            abort(403);
        }

        $service = new ReportCsvExportService;
        $rows = collect();

        if (Schema::hasTable('news')) {
            News::cursor()->each(fn ($n) => $rows->push([
                'News', $n->title, $n->is_published ? 'Published' : 'Draft',
                $n->slug ?? '', $n->created_at->format('Y-m-d'), $n->published_at?->format('Y-m-d') ?? '',
            ]));
        }
        if (Schema::hasTable('events')) {
            Event::cursor()->each(fn ($e) => $rows->push([
                'Event', $e->title, $e->is_published ? 'Published' : 'Draft',
                $e->slug ?? '', $e->created_at->format('Y-m-d'), '',
            ]));
        }

        return $service->download('website-report.csv',
            ['Type', 'Title', 'Status', 'Slug', 'Created At', 'Published At'], $rows);
    }

    // ===================== Communication Report =====================

    public function communication(): View
    {
        if (Gate::denies('report.view')) {
            abort(403);
        }

        $stats = [
            'total_announcements' => Schema::hasTable('announcements') ? Announcement::count() : 0,
            'published_announcements' => Schema::hasTable('announcements') ? Announcement::where('is_published', true)->count() : 0,
            'draft_announcements' => Schema::hasTable('announcements') ? Announcement::where('is_published', false)->count() : 0,
            'total_notifications' => Schema::hasTable('notifications') ? Notification::count() : 0,
            'unread_notifications' => Schema::hasTable('notifications') ? Notification::where('is_read', false)->count() : 0,
            'total_messages' => Schema::hasTable('messages') ? Message::count() : 0,
            'unread_messages' => Schema::hasTable('messages') ? Message::where('is_read', false)->count() : 0,
        ];

        $recentAnnouncements = Schema::hasTable('announcements') ? Announcement::latest()->take(5)->get() : collect();

        return view('modules.reports.communication', compact('stats', 'recentAnnouncements'));
    }

    public function exportCommunication(): StreamedResponse
    {
        if (Gate::denies('report.view')) {
            abort(403);
        }

        $service = new ReportCsvExportService;
        $rows = collect();

        // Announcements
        if (Schema::hasTable('announcements')) {
            Announcement::with('creator')->cursor()->each(fn ($a) => $rows->push([
                'Announcement', $a->title, $a->target, $a->is_published ? 'Published' : 'Draft',
                $a->created_at->format('Y-m-d'),
            ]));
        }

        // Messages (safe metadata only - no body)
        if (Schema::hasTable('messages')) {
            Message::with(['sender', 'recipient'])->cursor()->each(fn ($m) => $rows->push([
                'Message', $m->subject, 'From: '.($m->sender->name ?? '-').' To: '.($m->recipient->name ?? '-'),
                $m->is_read ? 'Read' : 'Unread', $m->created_at->format('Y-m-d'),
            ]));
        }

        return $service->download('communication-report.csv',
            ['Type', 'Title/Subject', 'Target/Recipient', 'Status', 'Created At'], $rows);
    }
}
