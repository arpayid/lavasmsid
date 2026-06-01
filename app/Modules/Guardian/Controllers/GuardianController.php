<?php

namespace App\Modules\Guardian\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Guardian\Models\Guardian;
use App\Modules\Guardian\Requests\StoreGuardianRequest;
use App\Modules\Guardian\Requests\UpdateGuardianRequest;
use App\Modules\Guardian\Services\GuardianService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuardianController extends Controller
{
    public function __construct(protected GuardianService $service) {}

    public function index(Request $request): View
    {
        $query = Guardian::query();
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('phone', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        $guardians = $query->orderBy('name')->paginate(15);

        return view('modules.guardian.index', compact('guardians'));
    }

    public function create(): View
    {
        return view('modules.guardian.create');
    }

    public function store(StoreGuardianRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.guardians.index')->with('success', 'Orang tua/wali berhasil ditambahkan.');
    }

    public function show(Guardian $guardian): View
    {
        $guardian->load('user');

        return view('modules.guardian.show', compact('guardian'));
    }

    public function edit(Guardian $guardian): View
    {
        return view('modules.guardian.edit', compact('guardian'));
    }

    public function update(UpdateGuardianRequest $request, Guardian $guardian): RedirectResponse
    {
        $this->service->update($guardian, $request->validated());

        return redirect()->route('admin.guardians.index')->with('success', 'Orang tua/wali berhasil diperbarui.');
    }

    public function destroy(Guardian $guardian): RedirectResponse
    {
        $this->service->delete($guardian);

        return redirect()->route('admin.guardians.index')->with('success', 'Orang tua/wali berhasil dihapus.');
    }
}
