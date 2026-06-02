<?php

namespace App\Modules\IndustryPartner\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\IndustryPartner\Models\IndustryPartner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class IndustryPartnerController extends Controller
{
    public function index(Request $request): View
    {
        if (Gate::denies('industry.view')) {
            abort(403);
        }

        $query = IndustryPartner::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%')
                ->orWhere('sector', 'like', '%'.$request->search.'%');
        }

        $partners = $query->orderBy('name')->paginate(15);

        return view('modules.industry-partner.index', compact('partners'));
    }

    public function create(): View
    {
        if (Gate::denies('industry.create')) {
            abort(403);
        }

        return view('modules.industry-partner.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if (Gate::denies('industry.create')) {
            abort(403);
        }

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

    public function show(IndustryPartner $industryPartner): View
    {
        if (Gate::denies('industry.view')) {
            abort(403);
        }

        $industryPartner->load(['internships.student']);

        return view('modules.industry-partner.show', ['partner' => $industryPartner]);
    }

    public function edit(IndustryPartner $industryPartner): View
    {
        if (Gate::denies('industry.update')) {
            abort(403);
        }

        return view('modules.industry-partner.edit', ['partner' => $industryPartner]);
    }

    public function update(Request $request, IndustryPartner $industryPartner): RedirectResponse
    {
        if (Gate::denies('industry.update')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sector' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
        ]);

        $industryPartner->update($validated);

        return redirect()->route('admin.industry-partners.index')
            ->with('success', 'Mitra industri berhasil diperbarui.');
    }

    public function destroy(IndustryPartner $industryPartner): RedirectResponse
    {
        if (Gate::denies('industry.delete')) {
            abort(403);
        }

        if ($industryPartner->internships()->exists()) {
            return back()->with('error', 'Mitra industri tidak dapat dihapus karena masih memiliki data PKL.');
        }

        $industryPartner->delete();

        return redirect()->route('admin.industry-partners.index')
            ->with('success', 'Mitra industri berhasil dihapus.');
    }
}
