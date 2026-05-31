<x-admin-layout heading="Edit Lowongan Kerja">
    <form method="POST" action="{{ route('admin.bkk.vacancies.update', $vacancy) }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf @method('PUT')
        <div class="space-y-5">
            <x-admin.form-input name="title" label="Posisi/Jabatan" :value="old('title',$vacancy->title)" required />
            <x-admin.form-input name="company_name" label="Nama Perusahaan" :value="old('company_name',$vacancy->company_name)" required />
            <x-admin.form-select name="industry_partner_id" label="Mitra Industri" :options="$partners->pluck('name','id')->toArray()" :value="old('industry_partner_id',$vacancy->industry_partner_id)" placeholder="-- Pilih --" />
            <div class="grid gap-4 sm:grid-cols-2">
                <x-admin.form-input name="location" label="Lokasi" :value="old('location',$vacancy->location)" />
                <x-admin.form-input name="salary_range" label="Kisaran Gaji" :value="old('salary_range',$vacancy->salary_range)" />
            </div>
            <x-admin.form-textarea name="description" label="Deskripsi Lowongan" :value="old('description',$vacancy->description)" rows="4" />
            <div class="grid gap-4 sm:grid-cols-2">
                <x-admin.form-input name="deadline" label="Deadline" type="date" :value="old('deadline',$vacancy->deadline?->format('Y-m-d'))" />
                <x-admin.form-select name="status" label="Status" :options="['active'=>'Aktif','closed'=>'Tutup']" :value="old('status',$vacancy->status)" />
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white">Simpan</button>
            <a href="{{ route('admin.bkk.vacancies.index') }}" class="rounded-lg border px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a>
        </div>
    </form>
</x-admin-layout>
