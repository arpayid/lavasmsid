<?php

namespace App\Modules\BKK\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Alumni\Models\Alumni;
use App\Modules\Alumni\Models\JobApplication;
use App\Modules\Alumni\Models\JobVacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BkkReportController extends Controller
{
    /**
     * Display BKK report index.
     */
    public function index(Request $request): View
    {
        if (Gate::denies('bkk.view')) {
            abort(403);
        }

        $stats = [
            'total_alumni' => Alumni::count(),
            'working' => Alumni::where('status', 'working')->count(),
            'studying' => Alumni::where('status', 'studying')->count(),
            'entrepreneur' => Alumni::where('status', 'entrepreneur')->count(),
            'unemployed' => Alumni::where('status', 'unemployed')->count(),
            'unknown' => Alumni::where('status', 'unknown')->count(),

            'total_vacancies' => JobVacancy::count(),
            'active_vacancies' => JobVacancy::where('status', 'active')->count(),
            'closed_vacancies' => JobVacancy::where('status', 'closed')->count(),

            'total_applications' => JobApplication::count(),
            'applied' => JobApplication::where('status', 'applied')->count(),
            'interview' => JobApplication::where('status', 'interview')->count(),
            'hired' => JobApplication::where('status', 'hired')->count(),
            'rejected' => JobApplication::where('status', 'rejected')->count(),
        ];

        $recentHired = JobApplication::with(['alumni', 'vacancy'])
            ->where('status', 'hired')
            ->latest('updated_at')
            ->take(5)
            ->get();

        $activeVacancies = JobVacancy::where('status', 'active')
            ->latest()
            ->take(5)
            ->get();

        return view('modules.bkk.reports.index', compact('stats', 'recentHired', 'activeVacancies'));
    }

    /**
     * Export alumni data to CSV.
     */
    public function alumniExport(Request $request): StreamedResponse
    {
        if (Gate::denies('alumni.export')) {
            abort(403);
        }

        $fileName = 'alumni_report_'.date('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($request) {
            $handle = fopen('php://output', 'w');

            // BOM for Excel
            fwrite($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['Nama', 'Tahun Lulus', 'Email', 'Telepon', 'Status Pekerjaan', 'Tempat Kerja/Usaha', 'Jabatan', 'Nama Kampus', 'Program Studi']);

            $query = Alumni::query();
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('graduation_year')) {
                $query->where('graduation_year', $request->graduation_year);
            }

            foreach ($query->cursor() as $row) {
                fputcsv($handle, [
                    $row->name,
                    $row->graduation_year,
                    $row->email,
                    $row->phone,
                    $row->status,
                    $row->company_name,
                    $row->job_title,
                    $row->institution_name,
                    $row->study_program,
                ]);
            }

            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Export vacancy data to CSV.
     */
    public function vacancyExport(Request $request): StreamedResponse
    {
        if (Gate::denies('bkk.export')) {
            abort(403);
        }

        $fileName = 'vacancy_report_'.date('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($request) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['Judul Lowongan', 'Perusahaan', 'Lokasi', 'Tipe', 'Status', 'Deadline', 'Gaji Min', 'Gaji Max']);

            $query = JobVacancy::query();
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            foreach ($query->cursor() as $row) {
                fputcsv($handle, [
                    $row->title,
                    $row->company_name,
                    $row->location,
                    $row->type,
                    $row->status,
                    $row->deadline?->format('Y-m-d'),
                    $row->salary_min,
                    $row->salary_max,
                ]);
            }

            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Export application data to CSV.
     */
    public function applicationExport(Request $request): StreamedResponse
    {
        if (Gate::denies('bkk.export')) {
            abort(403);
        }

        $fileName = 'application_report_'.date('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($request) {
            $handle = fopen('php://output', 'w');
            fwrite($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, ['Nama Alumni', 'Lowongan', 'Perusahaan', 'Status', 'Tanggal Lamar', 'Update Terakhir']);

            $query = JobApplication::with(['alumni', 'vacancy']);
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            foreach ($query->cursor() as $row) {
                fputcsv($handle, [
                    $row->alumni->name ?? '-',
                    $row->vacancy->title ?? '-',
                    $row->vacancy->company_name ?? '-',
                    $row->status,
                    $row->applied_at?->format('Y-m-d') ?? $row->created_at->format('Y-m-d'),
                    $row->updated_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
