<x-admin-layout heading="Tambah PKL">
    <form method="POST" action="{{ route('admin.internships.store') }}" class="max-w-xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        <div class="space-y-5">
            <x-admin.form-select name="student_id" label="Siswa" :options="$students->pluck('name','id')->toArray()" :value="old('student_id')" required />
            <x-admin.form-select name="industry_partner_id" label="Mitra Industri" :options="$partners->pluck('name','id')->toArray()" :value="old('industry_partner_id')" required />
            <x-admin.form-input name="teacher_id" label="ID Guru Pembimbing" type="number" :value="old('teacher_id')" />
            <div class="grid grid-cols-2 gap-4">
                <x-admin.form-input name="start_date" label="Tanggal Mulai" type="date" :value="old('start_date')" required />
                <x-admin.form-input name="end_date" label="Tanggal Selesai" type="date" :value="old('end_date')" required />
            </div>
            <x-admin.form-select name="status" label="Status" :options="array_combine($statuses, ['Direncanakan','Berlangsung','Selesai','Dibatalkan'])" :value="old('status','planned')" />
            <x-admin.form-input name="grade" label="Nilai (0-100)" type="number" step="0.01" :value="old('grade')" />
            <x-admin.form-textarea name="notes" label="Catatan" :value="old('notes')" rows="3" />
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white">Simpan</button>
            <a href="{{ route('admin.internships.index') }}" class="rounded-lg border px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a>
        </div>
    </form>
</x-admin-layout>