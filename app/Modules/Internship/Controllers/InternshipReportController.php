<?php

namespace App\Modules\Internship\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\IndustryPartner\Models\IndustryPartner;
use App\Modules\Internship\Models\Internship;
use App\Modules\Internship\Models\InternshipLog;
use App\Modules\Internship\Models\InternshipMonitoring;
use App\Modules\Student\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InternshipReportController extends Controller
{
    public function index(Request $request): View
    {
        if (Gate::denies('internship.view')) {
            abort(403);
        }

        $query = Internship::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('industry_partner_id')) {
            $query->where('industry_partner_id', $request->industry_partner_id);
        }
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('end_date', '<=', $request->date_to);
        }

        $totalInternships = (clone $query)->count();
        $plannedCount = (clone $query)->where('status', 'planned')->count();
        $ongoingCount = (clone $query)->where('status', 'ongoing')->count();
        $completedCount = (clone $query)->where('status', 'completed')->count();
        $cancelledCount = (clone $query)->where('status', 'cancelled')->count();

        $averageGrade = (clone $query)->whereNotNull('grade')->avg('grade') ?? 0;

        $totalPartners = IndustryPartner::count();
        $totalLogs = InternshipLog::whereIn('internship_id', (clone $query)->pluck('id'))->count();
        $totalMonitorings = InternshipMonitoring::whereIn('internship_id', (clone $query)->pluck('id'))->count();

        $recentCompleted = (clone $query)->with(['student', 'industryPartner', 'score'])
            ->where('status', 'completed')
            ->orderByDesc('end_date')
            ->take(10)
            ->get();

        return view('modules.internship.reports.index', [
            'totalPartners' => $totalPartners,
            'totalInternships' => $totalInternships,
            'plannedCount' => $plannedCount,
            'ongoingCount' => $ongoingCount,
            'completedCount' => $completedCount,
            'cancelledCount' => $cancelledCount,
            'averageGrade' => $averageGrade,
            'totalLogs' => $totalLogs,
            'totalMonitorings' => $totalMonitorings,
            'recentCompleted' => $recentCompleted,
            'partners' => IndustryPartner::orderBy('name')->get(),
            'students' => Student::orderBy('name')->get(),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        // Using internship.view because there is no specific export permission yet
        if (Gate::denies('internship.view')) {
            abort(403);
        }

        $fileName = 'internship_report_'.now()->format('Ymd_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        return response()->streamDownload(function () use ($request) {
            $handle = fopen('php://output', 'w');

            // BOM for Excel
            fwrite($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'ID PKL', 'Siswa', 'Mitra Industri', 'Guru Pembimbing',
                'Tgl Mulai', 'Tgl Selesai', 'Status', 'Nilai Akhir', 'Predikat',
            ]);

            $query = Internship::with(['student', 'industryPartner', 'user', 'score']);

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('industry_partner_id')) {
                $query->where('industry_partner_id', $request->industry_partner_id);
            }
            if ($request->filled('student_id')) {
                $query->where('student_id', $request->student_id);
            }
            if ($request->filled('date_from')) {
                $query->where('start_date', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->where('end_date', '<=', $request->date_to);
            }

            foreach ($query->cursor() as $internship) {
                fputcsv($handle, [
                    $internship->id,
                    $internship->student->name ?? '-',
                    $internship->industryPartner->name ?? '-',
                    $internship->user->name ?? '-',
                    $internship->start_date?->format('Y-m-d'),
                    $internship->end_date?->format('Y-m-d'),
                    $internship->status,
                    $internship->grade ?? '-',
                    $internship->score->predicate ?? '-',
                ]);
            }

            fclose($handle);
        }, $fileName, $headers);
    }
}
