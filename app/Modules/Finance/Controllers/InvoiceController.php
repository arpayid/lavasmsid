<?php

namespace App\Modules\Finance\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\PaymentCategory;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        if (Gate::denies('finance.view')) {
            abort(403);
        }

        $query = Invoice::with(['student', 'paymentCategory']);

        if ($request->filled('search')) {
            $query->where('invoice_number', 'like', '%'.$request->search.'%')
                ->orWhereHas('student', function ($q) use ($request) {
                    $q->where('name', 'like', '%'.$request->search.'%');
                });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->latest()->paginate(15);

        return view('modules.finance.invoices.index', compact('invoices'));
    }

    public function create(): View
    {
        if (Gate::denies('finance.create')) {
            abort(403);
        }

        $students = Student::orderBy('name')->get();
        $categories = PaymentCategory::orderBy('name')->get();

        return view('modules.finance.invoices.create', compact('students', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (Gate::denies('finance.create')) {
            abort(403);
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'payment_category_id' => 'required|exists:payment_categories,id',
            'amount' => 'required|numeric|min:1',
            'due_date' => 'nullable|date',
            'invoice_number' => 'nullable|string|unique:invoices,invoice_number',
        ]);

        Invoice::create($validated);

        return redirect()->route('admin.finance.invoices.index')
            ->with('success', 'Tagihan berhasil dibuat.');
    }

    public function show(Invoice $invoice): View
    {
        if (Gate::denies('finance.view')) {
            abort(403);
        }

        $invoice->load(['student', 'paymentCategory']);

        return view('modules.finance.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice): View
    {
        if (Gate::denies('finance.update')) {
            abort(403);
        }

        $students = Student::orderBy('name')->get();
        $categories = PaymentCategory::orderBy('name')->get();

        return view('modules.finance.invoices.edit', compact('invoice', 'students', 'categories'));
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        if (Gate::denies('finance.update')) {
            abort(403);
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'payment_category_id' => 'required|exists:payment_categories,id',
            'amount' => 'required|numeric|min:1',
            'due_date' => 'nullable|date',
        ]);

        $invoice->update($validated);

        return redirect()->route('admin.finance.invoices.index')
            ->with('success', 'Tagihan berhasil diperbarui.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        if (Gate::denies('finance.update')) {
            abort(403);
        }

        if ($invoice->paid_amount > 0) {
            return back()->with('error', 'Tagihan yang sudah memiliki pembayaran tidak dapat dihapus.');
        }

        $invoice->delete();

        return redirect()->route('admin.finance.invoices.index')
            ->with('success', 'Tagihan berhasil dihapus.');
    }
}
