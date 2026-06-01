<x-admin-layout heading="Tambah Jurusan">
    <form method="POST" action="{{ route('admin.departments.store') }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        <div class="space-y-5">
            <x-admin.form-input name="code" label="Kode Jurusan" :value="old('code')" required placeholder="contoh: RPL" />
            <x-admin.form-input name="name" label="Nama Jurusan" :value="old('name')" required placeholder="contoh: Rekayasa Perangkat Lunak" />
            <x-admin.form-textarea name="description" label="Deskripsi" :value="old('description')" rows="3" />
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white">Simpan</button>
            <a href="{{ route('admin.departments.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a>
        </div>
    </form>
</x-admin-layout>
