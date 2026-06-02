<x-admin-layout heading="Tambah Alumni">
    <form method="POST" action="{{ route('admin.bkk.alumni.store') }}" class="max-w-4xl space-y-6 pb-12">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Data Dasar --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 space-y-5">
                <h3 class="font-bold text-slate-900 border-b pb-3">Informasi Dasar</h3>
                <x-admin.form-input name="name" label="Nama Lengkap" :value="old('name')" required />
                <x-admin.form-input name="nis" label="NIS" :value="old('nis')" />
                <x-admin.form-select name="department_id" label="Jurusan" :options="$departments->pluck('name','id')->toArray()" :value="old('department_id')" placeholder="-- Pilih --" />
                <x-admin.form-input name="graduation_year" label="Tahun Lulus" type="number" :value="old('graduation_year', date('Y'))" required min="2000" />
                <x-admin.form-input name="email" label="Email" type="email" :value="old('email')" />
                <x-admin.form-input name="phone" label="Telepon/WA" :value="old('phone')" />
                <x-admin.form-textarea name="address" label="Alamat Rumah" rows="2">{{ old('address') }}</x-admin.form-textarea>
            </div>

            {{-- Status & Karir --}}
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 space-y-5">
                <h3 class="font-bold text-slate-900 border-b pb-3">Status Karir & Akademik</h3>
                <x-admin.form-select name="status" label="Status Saat Ini" :options="['unemployed'=>'Belum Bekerja','working'=>'Bekerja','studying'=>'Kuliah','entrepreneur'=>'Wirausaha','unknown'=>'Tidak Diketahui']" :value="old('status', 'unknown')" required />

                <div class="pt-2 space-y-5">
                    <div class="rounded-xl bg-slate-50 p-4 border border-slate-100 space-y-4">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Detail Pekerjaan / Kuliah / Usaha</p>
                        <x-admin.form-input name="company_name" label="Nama Perusahaan / Kampus / Usaha" :value="old('company_name')" />
                        <x-admin.form-input name="job_title" label="Jabatan / Prodis" :value="old('job_title')" />
                        <x-admin.form-input name="salary_range" label="Range Pendapatan (opsional)" :value="old('salary_range')" />
                    </div>
                </div>

                <x-admin.form-textarea name="notes" label="Catatan Tambahan" rows="3">{{ old('notes') }}</x-admin.form-textarea>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700 shadow-md shadow-indigo-200">Simpan Data Alumni</button>
            <a href="{{ route('admin.bkk.alumni.index') }}" class="rounded-lg border border-slate-300 bg-white px-6 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Batal</a>
        </div>
    </form>
</x-admin-layout>
