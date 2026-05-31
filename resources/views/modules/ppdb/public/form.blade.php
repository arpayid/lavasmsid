<x-public-layout title="PPDB Online">
    <div class="mx-auto max-w-2xl py-12">
        <div class="rounded-2xl bg-white p-8 shadow-lg ring-1 ring-slate-200">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-slate-900">Pendaftaran Peserta Didik Baru</h1>
                <p class="mt-2 text-slate-500">Isi form di bawah untuk mendaftar.</p>
            </div>

            <form method="POST" action="{{ route('public.ppdb.submit') }}" class="space-y-5">
                @csrf

                <h2 class="font-semibold text-slate-900 border-b pb-2">Data Calon Siswa</h2>

                <x-admin.form-input name="candidate_name" label="Nama Lengkap" :value="old('candidate_name')" required />
                <x-admin.form-select name="gender" label="Jenis Kelamin" :options="['L' => 'Laki-laki', 'P' => 'Perempuan']" :value="old('gender')" required />
                <x-admin.form-select name="department_id" label="Jurusan" :options="$departments->pluck('name', 'id')->toArray()" :value="old('department_id')" placeholder="-- Pilih Jurusan --" />
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-admin.form-input name="birth_place" label="Tempat Lahir" :value="old('birth_place')" />
                    <x-admin.form-input name="birth_date" label="Tanggal Lahir" type="date" :value="old('birth_date')" />
                </div>
                <x-admin.form-input name="previous_school" label="Asal Sekolah" :value="old('previous_school')" placeholder="Nama SMP/MTs sebelumnya" />
                <x-admin.form-textarea name="address" label="Alamat" :value="old('address')" rows="3" />
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-admin.form-input name="email" label="Email" type="email" :value="old('email')" />
                    <x-admin.form-input name="phone" label="No. Telepon/HP" :value="old('phone')" />
                </div>

                <h2 class="font-semibold text-slate-900 border-b pb-2 pt-4">Data Orang Tua</h2>

                <x-admin.form-input name="parent_name" label="Nama Orang Tua/Wali" :value="old('parent_name')" />
                <x-admin.form-input name="parent_phone" label="No. Telepon Orang Tua" :value="old('parent_phone')" />

                <div class="pt-4">
                    <button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-3 text-sm font-semibold text-white hover:bg-indigo-700 transition">
                        Daftar Sekarang
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-500">Sudah mendaftar? <a href="{{ route('public.ppdb.check') }}" class="text-indigo-600 hover:underline">Cek Status Pendaftaran</a></p>
            </div>
        </div>
    </div>
</x-public-layout>