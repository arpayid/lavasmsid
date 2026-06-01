<?php

namespace App\Modules\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SchoolSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'logoPath' => $setting?->logo_path ?? '',
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_name' => ['required', 'string', 'max:255'],
            'school_email' => ['nullable', 'email', 'max:255'],
            'school_phone' => ['nullable', 'string', 'max:50'],
            'school_address' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        $setting = SchoolSetting::firstOrCreateDefault();

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($setting->logo_path && Storage::disk('public')->exists($setting->logo_path)) {
                Storage::disk('public')->delete($setting->logo_path);
            }

            $validated['logo_path'] = $request->file('logo')->store('settings', 'public');
        }

        unset($validated['logo']);

        $setting->update($validated);

        return back()->with('success', 'Pengaturan sekolah berhasil disimpan.');
    }
}
