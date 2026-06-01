<?php

namespace App\Modules\Finance\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\PaymentService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    public function index(Request $request): View
    {
        if (Gate::denies('finance.view')) {
            abort(403);
        }

        $query = Payment::with(['invoice.student', 'invoice.paymentCategory', 'verifier']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->latest()->paginate(15);

        return view('modules.finance.payments.index', compact('payments'));
    }

    public function create(Request $request): View
    {
        if (Gate::denies('finance.create')) {
            abort(403);
        }

        $invoice = null;
        if ($request->filled('invoice_id')) {
            $invoice = Invoice::with(['student', 'paymentCategory'])->find($request->invoice_id);
        }

        $invoices = Invoice::with(['student', 'paymentCategory'])
            ->where('status', '!=', 'paid')
            ->orderByDesc('created_at')
            ->get();

        return view('modules.finance.payments.create', compact('invoice', 'invoices'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (Gate::denies('finance.create')) {
            abort(403);
        }

        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'amount' => 'required|numeric|min:1',
            'paid_at' => 'required|date',
            'method' => 'required|string|max:50',
            'receipt_number' => 'nullable|string|unique:payments,receipt_number',
        ]);

        try {
            // Auto-verify if user has permission
            $verifierId = Gate::allows('finance.verify') ? auth()->id() : null;

            $payment = $this->paymentService->recordPayment($validated, $verifierId);

            return redirect()->route('admin.finance.invoices.show', $payment->invoice_id)
                ->with('success', $payment->status === 'verified' ? 'Pembayaran berhasil dicatat dan diverifikasi.' : 'Pembayaran berhasil dicatat dan menunggu verifikasi.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Payment $payment): View
    {
        if (Gate::denies('finance.view')) {
            abort(403);
        }

        $payment->load(['invoice.student', 'invoice.paymentCategory', 'verifier']);

        return view('modules.finance.payments.show', compact('payment'));
    }

    public function verify(Payment $payment): RedirectResponse
    {
        if (Gate::denies('finance.verify')) {
            abort(403);
        }

        try {
            $this->paymentService->verifyPayment($payment->id, auth()->id());

            return back()->with('success', 'Pembayaran berhasil diverifikasi.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        if (Gate::denies('finance.update')) {
            abort(403);
        }

        if ($payment->status === 'verified') {
            return back()->with('error', 'Pembayaran yang sudah diverifikasi tidak dapat dihapus.');
        }

        $payment->delete();

        return redirect()->route('admin.finance.payments.index')
            ->with('success', 'Pembayaran pending berhasil dihapus.');
    }
}
