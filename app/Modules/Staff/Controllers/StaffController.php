<?php

namespace App\Modules\Staff\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Staff\Models\Staff;
use App\Modules\Staff\Requests\StoreStaffRequest;
use App\Modules\Staff\Requests\UpdateStaffRequest;
use App\Modules\Staff\Services\StaffService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function __construct(protected StaffService $service) {}

    public function index(Request $request): View
    {
        $query = Staff::query();
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                    ->orWhere('employee_number', 'like', '%'.$request->search.'%')
                    ->orWhere('email', 'like', '%'.$request->search.'%')
                    ->orWhere('position', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        $staff = $query->orderBy('name')->paginate(15);

        return view('modules.staff.index', compact('staff'));
    }

    public function create(): View
    {
        return view('modules.staff.create');
    }

    public function store(StoreStaffRequest $request): RedirectResponse
    {
        $this->service->create($request->validated());

        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil ditambahkan.');
    }

    public function show(Staff $staff): View
    {
        $staff->load('user');

        return view('modules.staff.show', compact('staff'));
    }

    public function edit(Staff $staff): View
    {
        return view('modules.staff.edit', compact('staff'));
    }

    public function update(UpdateStaffRequest $request, Staff $staff): RedirectResponse
    {
        $this->service->update($staff, $request->validated());

        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil diperbarui.');
    }

    public function destroy(Staff $staff): RedirectResponse
    {
        $this->service->delete($staff);

        return redirect()->route('admin.staff.index')->with('success', 'Staff berhasil dihapus.');
    }
}
