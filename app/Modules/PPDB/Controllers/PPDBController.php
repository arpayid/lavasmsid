<?php

namespace App\Modules\PPDB\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\PPDB\Models\PpdbRegistration;
use App\Modules\PPDB\Services\PpdbService;
use App\Modules\Academic\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PPDBController extends Controller
{
    public function __construct(protected PpdbService $ppdbService) {}

    public function index(Request $request): View
    {
        $query = PpdbRegistration::with('department');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $registrations = $query->orderByDesc('created_at')->paginate(20);

        $stats = [
            'all' => PpdbRegistration::count(),
            'submitted' => PpdbRegistration::where('status', 'submitted')->count(),
            'verified' => PpdbRegistration::where('status', 'verified')->count(),
            'accepted' => PpdbRegistration::where('status', 'accepted')->count(),
            'rejected' => PpdbRegistration::where('status', 'rejected')->count(),
        ];

        return view('modules.ppdb.index', compact('registrations', 'stats'));
    }

    public function show(PpdbRegistration $ppdb): View
    {
        $ppdb->load('department');

        return view('modules.ppdb.show', compact('ppdb'));
    }

    public function verify(PpdbRegistration $ppdb): RedirectResponse
    {
        $this->ppdbService->verify($ppdb->id);

        return back()->with('success', 'Berkas pendaftar telah diverifikasi.');
    }

    public function accept(PpdbRegistration $ppdb): RedirectResponse
    {
        $this->ppdbService->accept($ppdb->id);

        return back()->with('success', 'Pendaftar diterima.');
    }

    public function reject(Request $request, PpdbRegistration $ppdb): RedirectResponse
    {
        $validated = $request->validate(['notes' => ['nullable', 'string']]);

        $this->ppdbService->reject($ppdb->id, $validated['notes'] ?? null);

        return back()->with('success', 'Pendaftar ditolak.');
    }

    public function convert(PpdbRegistration $ppdb): RedirectResponse
    {
        if ($ppdb->status !== PpdbRegistration::STATUS_ACCEPTED) {
            return back()->with('error', 'Hanya pendaftar berstatus diterima yang bisa dikonversi.');
        }

        $studentId = $this->ppdbService->convertToStudent($ppdb);

        return redirect()->route('admin.students.show', $studentId)
            ->with('success', 'Pendaftar berhasil dikonversi menjadi siswa aktif.');
    }

    public function settings(): View
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('modules.ppdb.settings', compact('departments'));
    }
}
