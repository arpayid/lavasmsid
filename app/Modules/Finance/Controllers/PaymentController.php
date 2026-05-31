<?php

namespace App\Modules\Finance\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Finance\Models\{Invoice, Payment};
use App\Modules\Finance\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    public function dashboard(): View
    {
        // Simple dashboard stats
        $stats = [
            'total_income' => Payment::where('status', Payment::STATUS_VERIFIED)->sum('amount'),
            'income_today' => Payment::where('status', Payment::STATUS_VERIFIED)->whereDate('paid_at', today())->sum('amount'),
            'unpaid_invoices' => Invoice::where('status', '!=', Invoice::STATUS_PAID)->count(),
            'pending_verifications' => Payment::where('status', Payment::STATUS_PENDING)->count(),
        ];

        $recentPayments = Payment::with(['invoice.student', 'invoice.paymentCategory'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('modules.finance.dashboard', compact('stats', 'recentPayments'));
    }

    public function index(Request $request): View
    {
        $query = Payment::with(['invoice.student', 'invoice.paymentCategory', 'verifier']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('paid_at', $request->date);
        }

        $payments = $query->orderByDesc('created_at')->paginate(20);

        return view('modules.finance.payments.index', compact('payments'));
    }

    public function create(Request $request): View
    {
        $invoice = null;
        if ($request->filled('invoice_id')) {
            $invoice = Invoice::with(['student', 'paymentCategory'])->find($request->invoice_id);
        }

        $invoices = Invoice::with(['student', 'paymentCategory'])
            ->where('status', '!=', Invoice::STATUS_PAID)
            ->orderByDesc('created_at')
            ->get();

        return view('modules.finance.payments.create', compact('invoice', 'invoices'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'invoice_id' => ['required', 'exists:invoices,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'paid_at' => ['required', 'date'],
            'method' => ['required', 'in:cash,transfer,qris'],
        ]);

        $invoice = Invoice::findOrFail($validated['invoice_id']);
        if ($validated['amount'] > $invoice->getRemainingAmount()) {
            return back()->with('error', 'Jumlah pembayaran melebihi sisa tagihan.')->withInput();
        }

        $verifierId = auth()->user()->hasRole('Super Admin') || auth()->user()->can('finance.verify')
            ? auth()->id()
            : null;

        $this->paymentService->recordPayment($validated, $verifierId);

        return redirect()->route('admin.finance.invoices.show', $invoice)
            ->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function verify(Payment $payment): RedirectResponse
    {
        if (!auth()->user()->can('finance.verify')) {
            abort(403);
        }

        $this->paymentService->verifyPayment($payment->id, auth()->id());

        return back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }
}
