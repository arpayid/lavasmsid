<x-admin-layout heading="Pengaturan Sekolah">
    <form method="POST" action="{{ route('admin.settings.update') }}" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        @method('PUT')
        <h2 class="mb-6 text-lg font-semibold text-slate-900">Informasi Sekolah</h2>

        <div class="grid gap-5 md:grid-cols-2">
            <x-admin.form-input name="school_name" label="Nama Sekolah" :value="$schoolName" required />
            <x-admin.form-input name="school_email" label="Email Sekolah" type="email" placeholder="info@sekolah.id" />
            <x-admin.form-input name="school_phone" label="No. Telepon" placeholder="(0xxx) xxxxxxx" />
            <x-admin.form-input name="school_npsn" label="NPSN" placeholder="xxxxxxxx" />
        </div>

        <div class="mt-5">
            <x-admin.form-textarea name="school_address" label="Alamat Sekolah" rows="3" placeholder="Jl. Contoh No. 1, Kecamatan, Kota, Provinsi" />
        </div>

        <div class="mt-5">
            <x-admin.form-select name="school_timezone" label="Zona Waktu" :options="[
                'Asia/Jakarta' => 'WIB (UTC+7)',
                'Asia/Makassar' => 'WITA (UTC+8)',
                'Asia/Jayapura' => 'WIT (UTC+9)',
            ]" value="Asia/Makassar" />
        </div>

        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Simpan Pengaturan
            </button>
            <a href="{{ route('admin.dashboard') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                Batal
            </a>
        </div>
    </form>
</x-admin-layout>
