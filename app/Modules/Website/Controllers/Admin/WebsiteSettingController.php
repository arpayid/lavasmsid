<?php

namespace App\Modules\Website\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class WebsiteSettingController extends Controller
{
    public function edit(): View
    {
        if (Gate::denies('website.view')) {
            abort(403);
        }

        $settings = SchoolSetting::firstOrCreateDefault();

        return view('modules.website.settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        if (Gate::denies('website.update')) {
            abort(403);
        }

        $validated = $request->validate([
            'school_name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'school_email' => ['nullable', 'email', 'max:255'],
            'school_phone' => ['nullable', 'string', 'max:50'],
            'school_address' => ['nullable', 'string'],
            'principal_name' => ['nullable', 'string', 'max:255'],
            'principal_message' => ['nullable', 'string'],
            'history' => ['nullable', 'string'],
            'vision' => ['nullable', 'string'],
            'mission' => ['nullable', 'string'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'favicon' => ['nullable', 'image', 'max:1024'],
        ]);

        $settings = SchoolSetting::firstOrCreateDefault();

        if ($request->hasFile('logo')) {
            if ($settings->logo_path) {
                Storage::disk('public')->delete($settings->logo_path);
            }
            $validated['logo_path'] = $request->file('logo')->store('settings', 'public');
        }

        if ($request->hasFile('favicon')) {
            if ($settings->favicon_path) {
                Storage::disk('public')->delete($settings->favicon_path);
            }
            $validated['favicon_path'] = $request->file('favicon')->store('settings', 'public');
        }

        $settings->update($validated);

        return back()->with('success', 'Pengaturan website berhasil diperbarui.');
    }
}
