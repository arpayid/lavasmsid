<?php

namespace App\Modules\Finance\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\PaymentCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PaymentCategoryController extends Controller
{
    public function index(Request $request): View
    {
        if (Gate::denies('finance.view')) {
            abort(403);
        }

        $query = PaymentCategory::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $categories = $query->orderBy('name')->paginate(15);

        return view('modules.finance.categories.index', compact('categories'));
    }

    public function create(): View
    {
        if (Gate::denies('finance.create')) {
            abort(403);
        }

        return view('modules.finance.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if (Gate::denies('finance.create')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        PaymentCategory::create($validated);

        return redirect()->route('admin.finance.categories.index')
            ->with('success', 'Kategori pembayaran berhasil ditambahkan.');
    }

    public function edit(PaymentCategory $category): View
    {
        if (Gate::denies('finance.update')) {
            abort(403);
        }

        return view('modules.finance.categories.edit', compact('category'));
    }

    public function update(Request $request, PaymentCategory $category): RedirectResponse
    {
        if (Gate::denies('finance.update')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $category->update($validated);

        return redirect()->route('admin.finance.categories.index')
            ->with('success', 'Kategori pembayaran berhasil diperbarui.');
    }

    public function destroy(PaymentCategory $category): RedirectResponse
    {
        if (Gate::denies('finance.update')) {
            abort(403);
        }

        if (Invoice::where('payment_category_id', $category->id)->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena sedang digunakan oleh tagihan.');
        }

        $category->delete();

        return redirect()->route('admin.finance.categories.index')
            ->with('success', 'Kategori pembayaran berhasil dihapus.');
    }
}
