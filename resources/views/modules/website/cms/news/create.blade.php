<x-admin-layout heading="Tambah Berita">
    <form method="POST" action="{{ route('admin.website.news.store') }}" enctype="multipart/form-data" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        <div class="space-y-5">
            <x-admin.form-input name="title" label="Judul Berita" :value="old('title')" required />
            <x-admin.form-input name="author" label="Penulis" :value="old('author', 'Admin')" />
            <x-admin.form-input name="image" label="Gambar" type="file" accept="image/*" />
            <x-admin.form-textarea name="content" label="Konten" :value="old('content')" rows="8" required />
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" id="is_published" class="rounded border-slate-300 text-indigo-600" @checked(old('is_published', true))>
                <label for="is_published" class="text-sm text-slate-700">Terbitkan</label>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white">Simpan</button>
            <a href="{{ route('admin.website.news.index') }}" class="rounded-lg border px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a>
        </div>
    </form>
</x-admin-layout>