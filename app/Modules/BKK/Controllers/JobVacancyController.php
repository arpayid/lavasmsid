<?php

namespace App\Modules\BKK\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Alumni\Models\JobVacancy;
use App\Modules\IndustryPartner\Models\IndustryPartner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class JobVacancyController extends Controller
{
    public function index(Request $request): View
    {
        if (Gate::denies('bkk.view')) {
            abort(403);
        }

        $query = JobVacancy::with('industryPartner')->orderByDesc('created_at');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->search.'%')
                    ->orWhere('company_name', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $vacancies = $query->paginate(20);

        return view('modules.bkk.vacancies.index', compact('vacancies'));
    }

    public function create(): View
    {
        if (Gate::denies('bkk.create')) {
            abort(403);
        }

        $partners = IndustryPartner::orderBy('name')->get();

        return view('modules.bkk.vacancies.create', compact('partners'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (Gate::denies('bkk.create')) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'industry_partner_id' => ['nullable', 'exists:industry_partners,id'],
            'company_name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0', 'gte:salary_min'],
            'status' => ['required', 'in:draft,active,closed'],
            'deadline' => ['nullable', 'date', function ($attribute, $value, $fail) use ($request) {
                if ($request->status === 'active' && $value && strtotime($value) < strtotime(date('Y-m-d'))) {
                    $fail('Batas waktu pendaftaran untuk lowongan aktif tidak boleh di masa lalu.');
                }
            }],
        ]);

        JobVacancy::create($validated);

        return redirect()->route('admin.bkk.vacancies.index')
            ->with('success', 'Lowongan berhasil ditambahkan.');
    }

    public function show(JobVacancy $vacancy): View
    {
        if (Gate::denies('bkk.view')) {
            abort(403);
        }

        $vacancy->load(['industryPartner', 'applications.alumni']);

        return view('modules.bkk.vacancies.show', compact('vacancy'));
    }

    public function edit(JobVacancy $vacancy): View
    {
        if (Gate::denies('bkk.update')) {
            abort(403);
        }

        $partners = IndustryPartner::orderBy('name')->get();

        return view('modules.bkk.vacancies.edit', compact('vacancy', 'partners'));
    }

    public function update(Request $request, JobVacancy $vacancy): RedirectResponse
    {
        if (Gate::denies('bkk.update')) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'industry_partner_id' => ['nullable', 'exists:industry_partners,id'],
            'company_name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'requirements' => ['nullable', 'string'],
            'salary_min' => ['nullable', 'numeric', 'min:0'],
            'salary_max' => ['nullable', 'numeric', 'min:0', 'gte:salary_min'],
            'status' => ['required', 'in:draft,active,closed'],
            'deadline' => ['nullable', 'date'],
        ]);

        $vacancy->update($validated);

        return redirect()->route('admin.bkk.vacancies.index')
            ->with('success', 'Lowongan berhasil diperbarui.');
    }

    public function destroy(JobVacancy $vacancy): RedirectResponse
    {
        if (Gate::denies('bkk.update')) {
            abort(403);
        }

        if ($vacancy->applications()->exists()) {
            return back()->with('error', 'Lowongan tidak dapat dihapus karena sudah memiliki pelamar.');
        }

        $vacancy->delete();

        return redirect()->route('admin.bkk.vacancies.index')
            ->with('success', 'Lowongan berhasil dihapus.');
    }
}
