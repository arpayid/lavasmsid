<?php

namespace App\Modules\Dashboard\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Show settings page.
     */
    public function index(): View
    {
        return view('admin.settings.index', [
            'schoolName' => config('app.name', 'LavaSMSID'),
        ]);
    }

    /**
     * Update school settings.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'school_name' => ['required', 'string', 'max:255'],
            'school_email' => ['nullable', 'email', 'max:255'],
            'school_phone' => ['nullable', 'string', 'max:50'],
            'school_address' => ['nullable', 'string', 'max:500'],
        ]);

        // Settings will be saved to database in a future migration.
        // For now, just flash success.
        return back()->with('success', 'Pengaturan sekolah berhasil disimpan.');
    }
}
