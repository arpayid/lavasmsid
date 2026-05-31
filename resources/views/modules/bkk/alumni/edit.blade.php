<x-admin-layout heading="Edit Alumni: {{ $alumni->name }}">
    <form method="POST" action="{{ route('admin.bkk.alumni.update', $alumni) }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf @method('PUT')
        <div class="space-y-5">
            <div class="grid gap-4 sm:grid-cols-2">
                <x-admin.form-input name="name" label="Nama Lengkap" :value="old('name',$alumni->name)" required />
                <x-admin.form-input name="nis" label="NIS" :value="old('nis',$alumni->nis)" />
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <x-admin.form-select name="department_id" label="Jurusan" :options="$departments->pluck('name','id')->toArray()" :value="old('department_id',$alumni->department_id)" placeholder="-- Pilih --" />
                <x-admin.form-input name="graduation_year" label="Tahun Lulus" type="number" :value="old('graduation_year',$alumni->graduation_year)" required />
            </div>
            <x-admin.form-select name="status" label="Status Saat Ini" :options="['working'=>'Bekerja','studying'=>'Kuliah','entrepreneur'=>'Wirausaha','unemployed'=>'Belum Bekerja']" :value="old('status',$alumni->status)" required />
            <h2 class="pt-4 border-t text-lg font-semibold text-slate-900">Detail</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <x-admin.form-input name="company_name" label="Nama Perusahaan" :value="old('company_name',$alumni->company_name)" />
                <x-admin.form-input name="job_title" label="Jabatan" :value="old('job_title',$alumni->job_title)" />
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <x-admin.form-input name="institution_name" label="Nama Institusi/Kampus" :value="old('institution_name',$alumni->institution_name)" />
                <x-admin.form-input name="study_program" label="Program Studi" :value="old('study_program',$alumni->study_program)" />
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <x-admin.form-input name="email" label="Email" type="email" :value="old('email',$alumni->email)" />
                <x-admin.form-input name="phone" label="Telepon" :value="old('phone',$alumni->phone)" />
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white">Simpan</button>
            <a href="{{ route('admin.bkk.alumni.index') }}" class="rounded-lg border px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a>
        </div>
    </form>
</x-admin-layout>
