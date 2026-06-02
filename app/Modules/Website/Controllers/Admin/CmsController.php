<?php

namespace App\Modules\Website\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Website\Models\Achievement;
use App\Modules\Website\Models\CmsPage;
use App\Modules\Website\Models\Event;
use App\Modules\Website\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CmsController extends Controller
{
    // --- CMS Pages ---
    public function pagesIndex(): View
    {
        if (Gate::denies('website.view')) {
            abort(403);
        }
        $pages = CmsPage::orderBy('title')->get();

        return view('modules.website.cms.pages.index', compact('pages'));
    }

    public function pagesEdit($slug): View
    {
        if (Gate::denies('website.update')) {
            abort(403);
        }
        $page = CmsPage::where('slug', $slug)->first();
        if (! $page) {
            $page = new CmsPage(['slug' => $slug, 'title' => ucfirst(str_replace('-', ' ', $slug))]);
        }

        return view('modules.website.cms.pages.edit', compact('page'));
    }

    public function pagesUpdate(Request $request, $slug): RedirectResponse
    {
        if (Gate::denies('website.update')) {
            abort(403);
        }
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
        ]);
        CmsPage::updateOrCreate(['slug' => $slug], $validated);

        return back()->with('success', 'Halaman berhasil disimpan.');
    }

    // --- Events ---
    public function eventsIndex(Request $request): View
    {
        if (Gate::denies('website.view')) {
            abort(403);
        }
        $query = Event::orderByDesc('start_date');
        $events = $query->paginate(15);

        return view('modules.website.cms.events.index', compact('events'));
    }

    public function eventsCreate(): View
    {
        if (Gate::denies('website.create')) {
            abort(403);
        }

        return view('modules.website.cms.events.create');
    }

    public function eventsStore(Request $request): RedirectResponse
    {
        if (Gate::denies('website.create')) {
            abort(403);
        }
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
        ]);
        $validated['is_published'] = $request->boolean('is_published');
        Event::create($validated);

        return redirect()->route('admin.website.events.index')->with('success', 'Agenda berhasil ditambahkan.');
    }

    public function eventsEdit(Event $event): View
    {
        if (Gate::denies('website.update')) {
            abort(403);
        }

        return view('modules.website.cms.events.edit', compact('event'));
    }

    public function eventsUpdate(Request $request, Event $event): RedirectResponse
    {
        if (Gate::denies('website.update')) {
            abort(403);
        }
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date'],
            'location' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
        ]);
        $validated['is_published'] = $request->boolean('is_published');
        $event->update($validated);

        return redirect()->route('admin.website.events.index')->with('success', 'Agenda berhasil diperbarui.');
    }

    public function eventsDestroy(Event $event): RedirectResponse
    {
        if (Gate::denies('website.delete')) {
            abort(403);
        }
        $event->delete();

        return redirect()->route('admin.website.events.index')->with('success', 'Agenda berhasil dihapus.');
    }

    // --- Facilities ---
    public function facilitiesIndex(): View
    {
        if (Gate::denies('website.view')) {
            abort(403);
        }
        $facilities = Facility::orderBy('sort_order')->get();

        return view('modules.website.cms.facilities.index', compact('facilities'));
    }

    public function facilitiesStore(Request $request): RedirectResponse
    {
        if (Gate::denies('website.create')) {
            abort(403);
        }
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $validated['is_active'] = $request->boolean('is_active');
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('facilities', 'public');
        }
        Facility::create($validated);

        return back()->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function facilitiesDestroy(Facility $facility): RedirectResponse
    {
        if (Gate::denies('website.delete')) {
            abort(403);
        }
        if ($facility->image_path) {
            Storage::disk('public')->delete($facility->image_path);
        }
        $facility->delete();

        return back()->with('success', 'Fasilitas berhasil dihapus.');
    }

    // --- Achievements ---
    public function achievementsIndex(Request $request): View
    {
        if (Gate::denies('website.view')) {
            abort(403);
        }
        $query = Achievement::orderByDesc('year');
        $achievements = $query->paginate(15);

        return view('modules.website.cms.achievements.index', compact('achievements'));
    }

    public function achievementsCreate(): View
    {
        if (Gate::denies('website.create')) {
            abort(403);
        }

        return view('modules.website.cms.achievements.create');
    }

    public function achievementsStore(Request $request): RedirectResponse
    {
        if (Gate::denies('website.create')) {
            abort(403);
        }
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'student_name' => ['nullable', 'string', 'max:255'],
            'level' => ['required', 'in:school,district,provincial,national,international'],
            'position' => ['nullable', 'string', 'max:255'],
            'year' => ['required', 'integer', 'min:2000', 'max:'.(date('Y') + 1)],
            'description' => ['nullable', 'string'],
        ]);
        Achievement::create($validated);

        return redirect()->route('admin.website.achievements.index')->with('success', 'Prestasi berhasil ditambahkan.');
    }

    public function achievementsEdit(Achievement $achievement): View
    {
        if (Gate::denies('website.update')) {
            abort(403);
        }

        return view('modules.website.cms.achievements.edit', compact('achievement'));
    }

    public function achievementsUpdate(Request $request, Achievement $achievement): RedirectResponse
    {
        if (Gate::denies('website.update')) {
            abort(403);
        }
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'student_name' => ['nullable', 'string', 'max:255'],
            'level' => ['required', 'in:school,district,provincial,national,international'],
            'position' => ['nullable', 'string', 'max:255'],
            'year' => ['required', 'integer', 'min:2000', 'max:'.(date('Y') + 1)],
            'description' => ['nullable', 'string'],
        ]);
        $achievement->update($validated);

        return redirect()->route('admin.website.achievements.index')->with('success', 'Prestasi berhasil diperbarui.');
    }

    public function achievementsDestroy(Achievement $achievement): RedirectResponse
    {
        if (Gate::denies('website.delete')) {
            abort(403);
        }
        $achievement->delete();

        return redirect()->route('admin.website.achievements.index')->with('success', 'Prestasi berhasil dihapus.');
    }
}
