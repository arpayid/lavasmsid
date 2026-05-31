<?php

namespace App\Modules\Communication\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Communication\Models\Announcement;
use App\Modules\Communication\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Announcement::with('creator')->orderByDesc('created_at');
        if ($request->filled('target')) {
            $query->where('target', $request->target);
        }
        $announcements = $query->paginate(15);

        return view('modules.communication.announcements.index', compact('announcements'));
    }

    public function create(): View
    {
        return view('modules.communication.announcements.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'target' => ['required', 'in:all,students,teachers,parents,staff'],
            'priority' => ['required', 'in:low,normal,high,urgent'],
            'is_published' => ['nullable', 'boolean'],
        ]);
        $validated['is_published'] = $request->boolean('is_published', true);
        $validated['created_by'] = auth()->id();
        $validated['published_at'] = $validated['is_published'] ? now() : null;
        $announcement = Announcement::create($validated);

        // Create notifications for target users
        if ($validated['is_published']) {
            $this->notifyUsers($announcement);
        }

        return redirect()->route('admin.communication.announcements.index')
            ->with('success', 'Pengumuman berhasil diterbitkan.');
    }

    public function edit(Announcement $announcement): View
    {
        return view('modules.communication.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'target' => ['required', 'in:all,students,teachers,parents,staff'],
            'priority' => ['required', 'in:low,normal,high,urgent'],
            'is_published' => ['nullable', 'boolean'],
        ]);
        $validated['is_published'] = $request->boolean('is_published', true);
        if ($validated['is_published'] && ! $announcement->is_published) {
            $validated['published_at'] = now();
        }
        $announcement->update($validated);

        return redirect()->route('admin.communication.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return redirect()->route('admin.communication.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }

    protected function notifyUsers(Announcement $announcement): void
    {
        $target = $announcement->target;
        $users = User::query();
        if ($target !== 'all') {
            $users->whereHas('roles', function ($q) use ($target) {
                $q->where('name', $target);
            });
        }
        $users->get()->each(function ($user) use ($announcement) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'announcement',
                'title' => 'Pengumuman Baru',
                'message' => $announcement->title,
                'action_url' => route('admin.communication.announcements.index'),
            ]);
        });
    }
}
