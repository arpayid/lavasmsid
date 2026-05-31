<?php

namespace App\Modules\IndustryPartner\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\IndustryPartner\Models\IndustryPartner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IndustryPartnerController extends Controller
{
    public function index(Request $request): View
    {
        $query = IndustryPartner::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $partners = $query->orderBy('name')->paginate(15);

        return view('modules.industry-partner.index', compact('partners'));
    }

    public function create(): View
    {
        return view('modules.industry-partner.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sector' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        IndustryPartner::create($validated);

        return redirect()->route('admin.industry-partners.index')
            ->with('success', 'Mitra industri berhasil ditambahkan.');
    }

    public function edit(IndustryPartner $industryPartner): View
    {
        return view('modules.industry-partner.edit', [
            'partner' => $industryPartner,
        ]);
    }

    public function update(Request $request, IndustryPartner $industryPartner): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sector' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $industryPartner->update($validated);

        return redirect()->route('admin.industry-partners.index')
            ->with('success', 'Mitra industri berhasil diperbarui.');
    }

    public function destroy(IndustryPartner $industryPartner): RedirectResponse
    {
        $industryPartner->delete();

        return redirect()->route('admin.industry-partners.index')
            ->with('success', 'Mitra industri berhasil dihapus.');
    }
}
