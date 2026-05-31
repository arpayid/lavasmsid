<?php

namespace App\Modules\BKK\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Alumni\Models\JobVacancy;
use App\Modules\Alumni\Models\JobApplication;
use App\Modules\IndustryPartner\Models\IndustryPartner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JobVacancyController extends Controller
{
    public function index(Request $request): View
    {
        $query = JobVacancy::with('industryPartner')->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $vacancies = $query->paginate(20);

        return view('modules.bkk.vacancies.index', compact('vacancies'));
    }

    public function create(): View
    {
        $partners = IndustryPartner::orderBy('name')->get();

        return view('modules.bkk.vacancies.create', compact('partners'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'industry_partner_id' => ['nullable', 'exists:industry_partners,id'],
            'company_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'salary_range' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:active,closed'],
            'deadline' => ['nullable', 'date'],
        ]);

        JobVacancy::create($validated);

        return redirect()->route('admin.bkk.vacancies.index')
            ->with('success', 'Lowongan berhasil ditambahkan.');
    }

    public function edit(JobVacancy $vacancy): View
    {
        $partners = IndustryPartner::orderBy('name')->get();

        return view('modules.bkk.vacancies.edit', compact('vacancy', 'partners'));
    }

    public function update(Request $request, JobVacancy $vacancy): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'industry_partner_id' => ['nullable', 'exists:industry_partners,id'],
            'company_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'salary_range' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:active,closed'],
            'deadline' => ['nullable', 'date'],
        ]);

        $vacancy->update($validated);

        return redirect()->route('admin.bkk.vacancies.index')
            ->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function destroy(JobVacancy $vacancy): RedirectResponse
    {
        $vacancy->delete();

        return redirect()->route('admin.bkk.vacancies.index')
            ->with('success', 'Lowongan berhasil dihapus.');
    }
}
