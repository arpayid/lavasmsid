<x-admin-layout heading="Edit Mata Pelajaran">
    <form method="POST" action="{{ route('admin.subjects.update', $subject) }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf @method('PUT')
        <div class="space-y-5">
            <x-admin.form-select name="department_id" label="Jurusan" :options="$departments->pluck('name', 'id')->toArray()" :value="old('department_id', $subject->department_id)" placeholder="Umum (semua jurusan)" />
            <x-admin.form-input name="code" label="Kode" :value="old('code', $subject->code)" required />
            <x-admin.form-input name="name" label="Nama Mata Pelajaran" :value="old('name', $subject->name)" required />
            <x-admin.form-select name="type" label="Tipe" :options="['general' => 'Umum', 'productive' => 'Produktif', 'vocational' => 'Kejuruan']" :value="old('type', $subject->type)" />
        </div>
        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">Simpan</button>
            <a href="{{ route('admin.subjects.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Batal</a>
        </div>
    </form>
</x-admin-layout>