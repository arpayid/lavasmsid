<?php

namespace App\Modules\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SchoolSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $setting = SchoolSetting::first();

        return view('admin.settings.index', [
            'schoolName' => $setting?->school_name ?? config('app.name', 'LavaSMSID'),
            'schoolEmail' => $setting?->school_email ?? '',
            'schoolPhone' => $setting?->school_phone ?? '',
            'schoolAddress' => $setting?->school_address ?? '',
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_name' => ['required', 'string', 'max:255'],
            'school_email' => ['nullable', 'email', 'max:255'],
            'school_phone' => ['nullable', 'string', 'max:50'],
            'school_address' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $setting = SchoolSetting::firstOrCreateDefault();

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('settings', 'public');
        }

        $setting->update($validated);

        return back()->with('success', 'Pengaturan sekolah berhasil disimpan.');
    }
}
