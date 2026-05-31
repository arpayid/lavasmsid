<x-admin-layout heading="Kelola Fasilitas">
    <div class="mb-6 grid gap-4 grid-cols-2 sm:grid-cols-3 md:grid-cols-4">
        @forelse($facilities as $f)
        <div class="rounded-xl bg-white shadow-sm ring-1 ring-slate-200 p-4">
            @if($f->image_path)
            <img src="{{ asset('storage/'.$f->image_path) }}" class="w-full h-32 object-cover rounded-lg mb-3" alt="">
            @endif
            <p class="font-medium text-sm">{{ $f->name }}</p>
            <p class="text-xs text-slate-500 mt-1">{{ Str::limit($f->description, 50) }}</p>
            <form method="POST" action="{{ route('admin.website.facilities.destroy', $f) }}" class="mt-2 text-right" onsubmit="return confirm('Hapus?')">
                @csrf @method('DELETE')<button type="submit" class="text-xs text-red-600">Hapus</button>
            </form>
        </div>
        @empty
        <div class="col-span-full py-16 text-center text-slate-500"><x-admin.empty-state title="Belum ada fasilitas" message="Tambahkan fasilitas." /></div>
        @endforelse
    </div>
    <form method="POST" action="{{ route('admin.website.facilities.store') }}" enctype="multipart/form-data" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 max-w-xl">
        @csrf
        <h2 class="mb-4 text-lg font-semibold">Tambah Fasilitas</h2>
        <div class="space-y-4">
            <x-admin.form-input name="name" label="Nama Fasilitas" required />
            <x-admin.form-input name="image" label="Gambar" type="file" accept="image/*" />
            <x-admin.form-textarea name="description" label="Deskripsi" rows="3" />
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" id="is_active" class="rounded border-slate-300 text-indigo-600" checked>
                <label for="is_active" class="text-sm text-slate-700">Aktif</label>
            </div>
        </div>
        <div class="mt-4"><button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">Simpan</button></div>
    </form>
</x-admin-layout>
