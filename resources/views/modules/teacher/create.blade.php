<x-admin-layout heading="Tambah Guru">
    <form method="POST" action="{{ route('admin.teachers.store') }}" enctype="multipart/form-data" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        <x-admin.form-input name="name" label="Nama Lengkap" :value="old('name')" required />
        <div class="grid gap-4 md:grid-cols-2">
            <x-admin.form-input name="nip" label="NIP" :value="old('nip')" />
            <x-admin.form-input name="nuptk" label="NUPTK" :value="old('nuptk')" />
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <x-admin.form-select name="gender" label="Jenis Kelamin" :options="['L' => 'Laki-laki', 'P' => 'Perempuan']" :value="old('gender')" />
            <x-admin.form-input name="birth_place" label="Tempat Lahir" :value="old('birth_place')" />
        </div>
        <x-admin.form-input name="birth_date" label="Tanggal Lahir" type="date" :value="old('birth_date')" />
        <x-admin.form-input name="email" label="Email" type="email" :value="old('email')" />
        <x-admin.form-input name="phone" label="Telepon" :value="old('phone')" />
        <x-admin.form-textarea name="address" label="Alamat" :value="old('address')" rows="2" />
        <div class="grid gap-4 md:grid-cols-2">
            <x-admin.form-input name="qualification" label="Pendidikan" :value="old('qualification')" />
            <x-admin.form-input name="certification_number" label="No. Sertifikasi" :value="old('certification_number')" />
        </div>
        <x-admin.form-select name="status" label="Status" :options="['active' => 'Aktif', 'inactive' => 'Nonaktif']" :value="old('status', 'active')" />
        <x-admin.form-input name="photo" label="Foto" type="file" accept="image/jpeg,image/png,image/webp" />
        <div class="mt-6 flex gap-3">
            <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white">Simpan</button>
            <a href="{{ route('admin.teachers.index') }}" class="rounded-lg border bg-white px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a>
        </div>
    </form>
</x-admin-layout>
