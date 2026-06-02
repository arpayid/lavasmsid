<x-admin-layout heading="Edit Halaman: {{ $page->title }}">
    <form method="POST" action="{{ route('admin.website.pages.update', $page->slug) }}" class="max-w-4xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf @method('PUT')
        <div class="space-y-5">
            <x-admin.form-input name="title" label="Judul Halaman" :value="old('title', $page->title)" required />
            <x-admin.form-textarea name="content" label="Konten Halaman" :value="old('content', $page->content)" rows="20" required />
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700 shadow-md">Simpan Halaman</button>
            <a href="{{ route('admin.website.pages.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Kembali</a>
        </div>
    </form>
</x-admin-layout>
