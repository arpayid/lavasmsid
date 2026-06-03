<x-admin-layout heading="Edit Jurusan">
    <x-admin.form method="PUT" action="{{ route('admin.departments.update', $department) }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <div class="space-y-5">
            <x-admin.form-input name="code" label="Kode Jurusan" :value="old('code', $department->code)" required />
            <x-admin.form-input name="name" label="Nama Jurusan" :value="old('name', $department->name)" required />
            <x-admin.form-textarea name="description" label="Deskripsi" :value="old('description', $department->description)" rows="3" />
        </div>
        <div class="mt-6 flex gap-3">
            <x-admin.button type="submit">Simpan</x-admin.button>
            <a href="{{ route('admin.departments.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a>
        </div>
    </x-admin.form>
</x-admin-layout>
