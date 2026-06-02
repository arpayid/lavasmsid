<x-admin-layout heading="Tambah Agenda">
    <form method="POST" action="{{ route('admin.website.events.store') }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        <div class="space-y-5">
            <x-admin.form-input name="title" label="Judul Agenda" :value="old('title')" required />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-admin.form-input name="start_date" label="Tanggal Mulai" type="date" :value="old('start_date')" required />
                <x-admin.form-input name="end_date" label="Tanggal Selesai (opsional)" type="date" :value="old('end_date')" />
            </div>
            <x-admin.form-input name="location" label="Lokasi" :value="old('location')" />
            <x-admin.form-textarea name="description" label="Deskripsi" :value="old('description')" rows="5" />
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" id="is_published" class="rounded border-slate-300 text-indigo-600" @checked(old('is_published', true))>
                <label for="is_published" class="text-sm text-slate-700">Terbitkan</label>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700 shadow-md">Simpan</button>
            <a href="{{ route('admin.website.events.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Batal</a>
        </div>
    </form>
</x-admin-layout>
