<?php

namespace App\Modules\Finance\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Finance\Models\PaymentCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentCategoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = PaymentCategory::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $categories = $query->orderBy('name')->paginate(15);

        return view('modules.finance.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('modules.finance.categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
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
        return view('modules.finance.categories.edit', compact('category'));
    }

    public function update(Request $request, PaymentCategory $category): RedirectResponse
    {
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
        $category->delete();

        return redirect()->route('admin.finance.categories.index')
            ->with('success', 'Kategori pembayaran berhasil dihapus.');
    }
}
