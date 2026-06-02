<?php

namespace App\Modules\Communication\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Communication\Models\Announcement;
use App\Modules\Communication\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        if (Gate::denies('communication.view')) {
            abort(403);
        }

        $query = Announcement::with('creator')->orderByDesc('created_at');
        if ($request->filled('target')) {
            $query->where('target', $request->target);
        }
        $announcements = $query->paginate(15);

        return view('modules.communication.announcements.index', compact('announcements'));
    }

    public function create(): View
    {
        if (Gate::denies('communication.create')) {
            abort(403);
        }

        return view('modules.communication.announcements.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if (Gate::denies('communication.create')) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'target' => ['required', 'in:all,students,teachers,parents,staff'],
            'priority' => ['required', 'in:low,normal,high,urgent'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        // Fix: Use boolean check that works for both 0/1 and checkbox absence
        $isPublished = $request->boolean('is_published');

        $validated['is_published'] = $isPublished;
        $validated['created_by'] = auth()->id();
        $validated['published_at'] = $isPublished ? now() : null;

        $announcement = Announcement::create($validated);

        if ($isPublished) {
            $this->notifyUsers($announcement);
        }

        return redirect()->route('admin.communication.announcements.index')
            ->with('success', 'Pengumuman berhasil '.($isPublished ? 'diterbitkan' : 'disimpan sebagai draft').'.');
    }

    public function show(Announcement $announcement): View
    {
        if (Gate::denies('communication.view')) {
            abort(403);
        }

        $announcement->load('creator');

        return view('modules.communication.announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement): View
    {
        if (Gate::denies('communication.update')) {
            abort(403);
        }

        return view('modules.communication.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        if (Gate::denies('communication.update')) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'target' => ['required', 'in:all,students,teachers,parents,staff'],
            'priority' => ['required', 'in:low,normal,high,urgent'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $isPublished = $request->boolean('is_published');
        $validated['is_published'] = $isPublished;

        if ($isPublished && ! $announcement->is_published) {
            $validated['published_at'] = now();
            $this->notifyUsers($announcement);
        } elseif (! $isPublished) {
            $validated['published_at'] = null;
        }

        $announcement->update($validated);

        return redirect()->route('admin.communication.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        if (Gate::denies('communication.delete')) {
            abort(403);
        }

        $announcement->delete();

        return redirect()->route('admin.communication.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }

    protected function notifyUsers(Announcement $announcement): void
    {
        $target = $announcement->target;
        $users = User::query();

        if ($target !== 'all') {
            $roleMap = [
                'students' => 'Siswa',
                'teachers' => 'Guru',
                'parents' => 'Orang Tua / Wali',
                'staff' => 'Staff TU',
            ];

            $roleName = $roleMap[$target] ?? null;

            if ($roleName) {
                $users->whereHas('roles', function ($q) use ($roleName) {
                    $q->where('name', $roleName);
                });
            }
        }

        $users->get()->each(function ($user) use ($announcement) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'announcement',
                'title' => 'Pengumuman Baru: '.$announcement->title,
                'message' => Str::limit(strip_tags($announcement->content), 100),
                'action_url' => route('admin.communication.announcements.index'),
            ]);
        });
    }
}
