<?php

namespace App\Modules\Website\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Website\Models\News;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        if (Gate::denies('website.view')) {
            abort(403);
        }

        $query = News::query();
        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }
        $news = $query->orderByDesc('created_at')->paginate(15);

        return view('modules.website.cms.news.index', compact('news'));
    }

    public function create(): View
    {
        if (Gate::denies('website.create')) {
            abort(403);
        }

        return view('modules.website.cms.news.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if (Gate::denies('website.create')) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'author' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        $validated['is_published'] = $request->has('is_published');
        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('news', 'public');
        }

        News::create($validated);

        return redirect()->route('admin.website.news.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(News $news): View
    {
        if (Gate::denies('website.update')) {
            abort(403);
        }

        return view('modules.website.cms.news.edit', compact('news'));
    }

    public function update(Request $request, News $news): RedirectResponse
    {
        if (Gate::denies('website.update')) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
            'author' => ['nullable', 'string', 'max:255'],
            'is_published' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
        ]);

        $validated['is_published'] = $request->has('is_published');

        if ($request->hasFile('image')) {
            if ($news->image_path) {
                Storage::disk('public')->delete($news->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('news', 'public');
        }

        $news->update($validated);

        return redirect()->route('admin.website.news.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $news): RedirectResponse
    {
        if (Gate::denies('website.delete')) {
            abort(403);
        }

        if ($news->image_path) {
            Storage::disk('public')->delete($news->image_path);
        }

        $news->delete();

        return redirect()->route('admin.website.news.index')->with('success', 'Berita berhasil dihapus.');
    }
}
