<?php

namespace App\Modules\Finance\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FinanceReportController extends Controller
{
    public function index(Request $request): View
    {
        if (Gate::denies('finance.view')) {
            abort(403);
        }

        $filters = $request->only([
            'date_from', 'date_to', 'payment_category_id', 'status', 'student_id',
        ]);

        // 1. Totals & Statistics
        $query = Invoice::query();
        if ($request->filled('payment_category_id')) {
            $query->where('payment_category_id', $request->payment_category_id);
        }
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->filled('date_from')) {
            $query->where('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('created_at', '<=', $request->date_to.' 23:59:59');
        }

        $stats = (clone $query)->selectRaw('COUNT(*) as total_invoices')
            ->selectRaw('COALESCE(SUM(amount), 0) as total_billed')
            ->selectRaw('COALESCE(SUM(paid_amount), 0) as total_paid')
            ->selectRaw("COALESCE(SUM(CASE WHEN status = 'unpaid' THEN 1 ELSE 0 END), 0) as unpaid_count")
            ->selectRaw("COALESCE(SUM(CASE WHEN status = 'partial' THEN 1 ELSE 0 END), 0) as partial_count")
            ->selectRaw("COALESCE(SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END), 0) as paid_count")
            ->first();

        $totalInvoices = $stats->total_invoices;
        $totalBilled = $stats->total_billed;
        $totalPaid = $stats->total_paid;
        $outstanding = $totalBilled - $totalPaid;
        $unpaidCount = $stats->unpaid_count;
        $partialCount = $stats->partial_count;
        $paidCount = $stats->paid_count;

        $paymentQuery = Payment::where('status', 'verified');
        if ($request->filled('date_from')) {
            $paymentQuery->where('paid_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $paymentQuery->where('paid_at', '<=', $request->date_to);
        }

        $verifiedPaymentCount = $paymentQuery->count();
        $pendingPaymentCount = Payment::where('status', 'pending')->count();

        // 2. Recent Data
        $recentPayments = Payment::with(['invoice.student'])
            ->where('status', 'verified')
            ->latest()
            ->take(10)
            ->get();

        $recentUnpaid = Invoice::with(['student', 'paymentCategory'])
            ->whereIn('status', ['unpaid', 'partial'])
            ->latest()
            ->take(10)
            ->get();

        return view('modules.finance.reports.index', [
            'totals' => [
                'totalInvoices' => $totalInvoices,
                'totalBilled' => $totalBilled,
                'totalPaid' => $totalPaid,
                'outstanding' => $outstanding,
                'unpaidCount' => $unpaidCount,
                'partialCount' => $partialCount,
                'paidCount' => $paidCount,
                'pendingPaymentCount' => $pendingPaymentCount,
                'verifiedPaymentCount' => $verifiedPaymentCount,
            ],
            'recentPayments' => $recentPayments,
            'recentUnpaid' => $recentUnpaid,
            'filters' => $filters,
            'categories' => PaymentCategory::orderBy('name')->get(),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        if (Gate::denies('finance.export')) {
            abort(403);
        }

        $fileName = 'finance_report_'.now()->format('Ymd_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        return response()->streamDownload(function () use ($request) {
            $handle = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($handle, ['Invoice No', 'Student Name', 'Category', 'Amount', 'Paid Amount', 'Balance', 'Status', 'Due Date']);

            $query = Invoice::with(['student', 'paymentCategory']);
            if ($request->filled('payment_category_id')) {
                $query->where('payment_category_id', $request->payment_category_id);
            }
            if ($request->filled('student_id')) {
                $query->where('student_id', $request->student_id);
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            foreach ($query->cursor() as $invoice) {
                fputcsv($handle, [
                    $invoice->invoice_number,
                    $invoice->student->name ?? '-',
                    $invoice->paymentCategory->name ?? '-',
                    $invoice->amount,
                    $invoice->paid_amount,
                    $invoice->getRemainingAmount(),
                    $invoice->status,
                    $invoice->due_date?->format('Y-m-d'),
                ]);
            }

            fclose($handle);
        }, $fileName, $headers);
    }
}
