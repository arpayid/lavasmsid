<?php

namespace App\Modules\Finance\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Finance\Models\{Invoice, PaymentCategory};
use App\Modules\Finance\Services\InvoiceService;
use App\Modules\Student\Models\Student;
use App\Modules\Academic\Models\Classroom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function __construct(protected InvoiceService $invoiceService) {}

    public function index(Request $request): View
    {
        $query = Invoice::with(['student', 'paymentCategory']);

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('category_id')) {
            $query->where('payment_category_id', $request->category_id);
        }

        $invoices = $query->orderByDesc('created_at')->paginate(20);
        $categories = PaymentCategory::orderBy('name')->get();
        $classrooms = Classroom::orderBy('name')->get();

        return view('modules.finance.invoices.index', compact('invoices', 'categories', 'classrooms'));
    }

    public function create(): View
    {
        return view('modules.finance.invoices.create', [
            'categories' => PaymentCategory::orderBy('name')->get(),
            'students' => Student::orderBy('name')->get(),
            'classrooms' => Classroom::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payment_category_id' => ['required', 'exists:payment_categories,id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'due_date' => ['nullable', 'date'],
            'target_type' => ['required', 'in:student,classroom,all'],
            'student_id' => ['required_if:target_type,student', 'nullable', 'exists:students,id'],
            'classroom_id' => ['required_if:target_type,classroom', 'nullable', 'exists:classrooms,id'],
        ]);

        $data = [
            'payment_category_id' => $validated['payment_category_id'],
            'amount' => $validated['amount'],
            'due_date' => $validated['due_date'],
        ];

        $studentIds = [];
        if ($validated['target_type'] === 'student') {
            $studentIds = [$validated['student_id']];
        } elseif ($validated['target_type'] === 'classroom') {
            $studentIds = Student::where('classroom_id', $validated['classroom_id'])->pluck('id')->toArray();
        } else {
            $studentIds = Student::pluck('id')->toArray();
        }

        if (empty($studentIds)) {
            return back()->with('error', 'Tidak ada siswa yang ditemukan untuk target ini.')->withInput();
        }

        $count = $this->invoiceService->bulkCreateInvoices($studentIds, $data);

        return redirect()->route('admin.finance.invoices.index')
            ->with('success', "Berhasil membuat $count tagihan.");
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['student', 'paymentCategory', 'payments.verifier']);

        return view('modules.finance.invoices.show', compact('invoice'));
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        if ($invoice->paid_amount > 0) {
            return back()->with('error', 'Tagihan yang sudah ada pembayaran tidak dapat dihapus.');
        }

        $invoice->delete();

        return redirect()->route('admin.finance.invoices.index')
            ->with('success', 'Tagihan berhasil dihapus.');
    }
}
