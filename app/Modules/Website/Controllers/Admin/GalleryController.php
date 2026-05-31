<?php

namespace App\Modules\Website\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Website\Models\Gallery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Gallery::orderBy('sort_order');
        if ($request->filled('category')) $query->where('category', $request->category);
        $galleries = $query->paginate(20);
        return view('modules.website.cms.gallery.index', compact('galleries'));
    }

    public function create(): View { return view('modules.website.cms.gallery.create'); }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'max:2048'],
            'category' => ['required', 'in:general,facility,event,achievement'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
        ]);
        $validated['image_path'] = $request->file('image')->store('gallery', 'public');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        Gallery::create($validated);
        return redirect()->route('admin.website.gallery.index')->with('success', 'Galeri berhasil ditambahkan.');
    }

    public function edit(Gallery $gallery): View { return view('modules.website.cms.gallery.edit', compact('gallery')); }

    public function update(Request $request, Gallery $gallery): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:2048'],
            'category' => ['required', 'in:general,facility,event,achievement'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
        ]);
        if ($request->hasFile('image')) $validated['image_path'] = $request->file('image')->store('gallery', 'public');
        $validated['sort_order'] = $validated['sort_order'] ?? $gallery->sort_order;
        $gallery->update($validated);
        return redirect()->route('admin.website.gallery.index')->with('success', 'Galeri berhasil diperbarui.');
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        $gallery->delete();
        return redirect()->route('admin.website.gallery.index')->with('success', 'Galeri berhasil dihapus.');
    }
}
