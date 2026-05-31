<?php

namespace App\Modules\PPDB\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Academic\Models\Department;
use App\Modules\PPDB\Models\PpdbRegistration;
use App\Modules\PPDB\Services\PpdbService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicPPDBController extends Controller
{
    public function __construct(protected PpdbService $ppdbService) {}

    public function form(): View
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('modules.ppdb.public.form', compact('departments'));
    }

    public function submit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'department_id' => ['nullable', 'exists:departments,id'],
            'candidate_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['required', 'in:L,P'],
            'previous_school' => ['nullable', 'string', 'max:255'],
            'parent_name' => ['nullable', 'string', 'max:255'],
            'parent_phone' => ['nullable', 'string', 'max:50'],
        ]);

        $registration = $this->ppdbService->register($validated);

        return redirect()->route('public.ppdb.status', $registration->registration_number)
            ->with('success', 'Pendaftaran berhasil! Simpan nomor pendaftaran Anda: '.$registration->registration_number);
    }

    public function status(string $number): View
    {
        $registration = PpdbRegistration::with('department')
            ->where('registration_number', $number)
            ->firstOrFail();

        return view('modules.ppdb.public.status', compact('registration'));
    }

    public function checkStatus(Request $request): View
    {
        $number = $request->input('registration_number');
        $registration = null;

        if ($number) {
            $registration = PpdbRegistration::with('department')
                ->where('registration_number', $number)
                ->first();
        }

        return view('modules.ppdb.public.check-status', compact('registration'));
    }
}
