<x-admin-layout heading="Kelola Galeri">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.website.gallery.create') }}" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">+ Tambah Galeri</a>
    </div>
    <div class="grid gap-4 grid-cols-2 sm:grid-cols-3 md:grid-cols-4">
        @forelse($galleries as $g)
        <div class="rounded-xl overflow-hidden bg-white shadow-sm ring-1 ring-slate-200 group">
            <img src="{{ asset('storage/'.$g->image_path) }}" class="w-full h-40 object-cover" alt="">
            <div class="p-3">
                <p class="font-medium text-sm">{{ Str::limit($g->title, 30) }}</p>
                <p class="text-xs text-slate-500">{{ $g->category }}</p>
                <div class="mt-2 flex justify-end gap-1">
                    <a href="{{ route('admin.website.gallery.edit', $g) }}" class="text-xs text-indigo-600">Edit</a>
                    <form method="POST" action="{{ route('admin.website.gallery.destroy', $g) }}" class="inline" onsubmit="return confirm('Hapus?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-600">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <p class="col-span-full text-center py-16 text-slate-500"><x-admin.empty-state title="Belum ada galeri" message="Tambahkan foto galeri." /></p>
        @endforelse
    </div>
    @if($galleries->hasPages())<div class="mt-4">{{ $galleries->links() }}</div>@endif
</x-admin-layout>
