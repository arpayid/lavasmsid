<x-admin-layout heading="Tambah Semester">
    <form method="POST" action="{{ route('admin.semesters.store') }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        <div class="space-y-5">
            <x-admin.form-select name="academic_year_id" label="Tahun Ajaran" :options="$academicYears->pluck('name', 'id')->toArray()" :value="old('academic_year_id')" required />
            <x-admin.form-input name="name" label="Nama Semester" :value="old('name')" required placeholder="contoh: Ganjil, Genap" />
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" id="is_active" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" @checked(old('is_active'))>
                <label for="is_active" class="text-sm text-slate-700">Semester Aktif</label>
            </div>
        </div>
        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">Simpan</button>
            <a href="{{ route('admin.semesters.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Batal</a>
        </div>
    </form>
</x-admin-layout>