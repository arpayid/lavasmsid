<x-admin-layout heading="Tambah Tahun Ajaran">
    <form method="POST" action="{{ route('admin.academic-years.store') }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        <div class="space-y-5">
            <x-admin.form-input name="name" label="Nama Tahun Ajaran" :value="old('name')" required placeholder="contoh: 2025/2026" />
            <x-admin.form-input name="start_date" label="Tanggal Mulai" type="date" :value="old('start_date')" required />
            <x-admin.form-input name="end_date" label="Tanggal Selesai" type="date" :value="old('end_date')" required />
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" id="is_active" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" @checked(old('is_active'))>
                <label for="is_active" class="text-sm text-slate-700">Tahun Ajaran Aktif</label>
            </div>
        </div>
        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">Simpan</button>
            <a href="{{ route('admin.academic-years.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Batal</a>
        </div>
    </form>
</x-admin-layout>
