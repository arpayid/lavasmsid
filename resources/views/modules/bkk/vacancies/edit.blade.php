<x-admin-layout heading="Edit Lowongan Kerja">
    <form method="POST" action="{{ route('admin.bkk.vacancies.update', $vacancy) }}" class="max-w-4xl space-y-6 pb-12">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 space-y-5">
                <h3 class="font-bold text-slate-900 border-b pb-3 text-sm uppercase tracking-wider">Informasi Dasar</h3>
                <x-admin.form-input name="title" label="Posisi / Jabatan" :value="old('title', $vacancy->title)" required />
                <x-admin.form-input name="company_name" label="Nama Perusahaan" :value="old('company_name', $vacancy->company_name)" required />
                <x-admin.form-select name="industry_partner_id" label="Mitra Industri Terdaftar (opsional)" :options="$partners->pluck('name','id')->toArray()" :value="old('industry_partner_id', $vacancy->industry_partner_id)" placeholder="-- Pilih Mitra --" />
                <x-admin.form-input name="location" label="Lokasi Kerja" :value="old('location', $vacancy->location)" />
                <x-admin.form-input name="type" label="Tipe Pekerjaan" :value="old('type', $vacancy->type)" />
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 space-y-5">
                <h3 class="font-bold text-slate-900 border-b pb-3 text-sm uppercase tracking-wider">Detail & Status</h3>
                <div class="grid grid-cols-2 gap-4">
                    <x-admin.form-input name="salary_min" label="Gaji Min" type="number" :value="old('salary_min', $vacancy->salary_min)" />
                    <x-admin.form-input name="salary_max" label="Gaji Max" type="number" :value="old('salary_max', $vacancy->salary_max)" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <x-admin.form-input name="deadline" label="Batas Waktu" type="date" :value="old('deadline', $vacancy->deadline?->format('Y-m-d'))" />
                    <x-admin.form-select name="status" label="Status" :options="['draft'=>'Draft','active'=>'Aktif','closed'=>'Tutup']" :value="old('status', $vacancy->status)" required />
                </div>
                <x-admin.form-textarea name="requirements" label="Persyaratan Khusus" rows="5">{{ old('requirements', $vacancy->requirements) }}</x-admin.form-textarea>
            </div>

            <div class="lg:col-span-2 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 space-y-5">
                <h3 class="font-bold text-slate-900 border-b pb-3 text-sm uppercase tracking-wider">Deskripsi Pekerjaan</h3>
                <x-admin.form-textarea name="description" label="Deskripsi Lengkap" rows="6">{{ old('description', $vacancy->description) }}</x-admin.form-textarea>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700 shadow-md shadow-indigo-200">Simpan Perubahan</button>
            <a href="{{ route('admin.bkk.vacancies.show', $vacancy) }}" class="rounded-lg border border-slate-300 bg-white px-6 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Batal</a>
        </div>
    </form>
</x-admin-layout>
