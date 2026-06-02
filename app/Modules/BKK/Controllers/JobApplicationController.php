<?php

namespace App\Modules\BKK\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Alumni\Models\Alumni;
use App\Modules\Alumni\Models\JobApplication;
use App\Modules\Alumni\Models\JobVacancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class JobApplicationController extends Controller
{
    public function index(Request $request): View
    {
        if (Gate::denies('bkk.view')) {
            abort(403);
        }

        $query = JobApplication::with(['alumni', 'vacancy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(20);

        return view('modules.bkk.applications.index', compact('applications'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (Gate::denies('bkk.update')) {
            abort(403);
        }

        $validated = $request->validate([
            'alumni_id' => ['required', 'exists:alumni,id'],
            'job_vacancy_id' => ['required', 'exists:job_vacancies,id'],
            'applied_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $vacancy = JobVacancy::findOrFail($validated['job_vacancy_id']);

        // Business rules
        if ($vacancy->status === 'closed') {
            return back()->with('error', 'Lowongan ini sudah ditutup.');
        }

        if ($vacancy->deadline && strtotime($vacancy->deadline) < strtotime(date('Y-m-d'))) {
            return back()->with('error', 'Batas waktu pendaftaran lowongan ini sudah berakhir.');
        }

        // Check for duplicate application
        $exists = JobApplication::where('alumni_id', $validated['alumni_id'])
            ->where('job_vacancy_id', $validated['job_vacancy_id'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Alumni ini sudah terdaftar sebagai pelamar di lowongan ini.');
        }

        JobApplication::create(array_merge($validated, [
            'status' => JobApplication::STATUS_APPLIED,
            'applied_at' => $validated['applied_at'] ?? date('Y-m-d'),
        ]));

        return back()->with('success', 'Lamaran kerja berhasil dicatat.');
    }

    public function show(JobApplication $application): View
    {
        if (Gate::denies('bkk.view')) {
            abort(403);
        }

        $application->load(['alumni.department', 'vacancy.industryPartner']);

        return view('modules.bkk.applications.show', compact('application'));
    }

    public function updateStatus(Request $request, JobApplication $application): RedirectResponse
    {
        if (Gate::denies('bkk.update')) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => ['required', 'in:applied,screening,interview,hired,rejected'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($application, $validated) {
            $application->update($validated);

            // If status hired, update alumni employment status
            if ($validated['status'] === JobApplication::STATUS_HIRED) {
                $alumnus = $application->alumni;
                $alumnus->update([
                    'status' => Alumni::STATUS_WORKING,
                    'company_name' => $application->vacancy->company_name,
                    'job_title' => $application->vacancy->title,
                ]);
            }
        });

        return back()->with('success', 'Status lamaran berhasil diperbarui.');
    }

    public function destroy(JobApplication $application): RedirectResponse
    {
        if (Gate::denies('bkk.update')) {
            abort(403);
        }

        $application->delete();

        return redirect()->route('admin.bkk.applications.index')
            ->with('success', 'Data lamaran berhasil dihapus.');
    }
}
