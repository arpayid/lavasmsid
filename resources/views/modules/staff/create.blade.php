<x-admin-layout heading="Tambah Staff">
    <form method="POST" action="{{ route('admin.staff.store') }}" enctype="multipart/form-data" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">@csrf
        <x-admin.form-input name="employee_number" label="NIK" :value="old('employee_number')" />
        <x-admin.form-input name="name" label="Nama Lengkap" :value="old('name')" required />
        <x-admin.form-input name="position" label="Jabatan" :value="old('position')" />
        <x-admin.form-select name="gender" label="Jenis Kelamin" :options="['L' => 'Laki-laki', 'P' => 'Perempuan']" :value="old('gender')" />
        <x-admin.form-input name="email" label="Email" type="email" :value="old('email')" />
        <x-admin.form-input name="phone" label="Telepon" :value="old('phone')" />
        <x-admin.form-textarea name="address" label="Alamat" :value="old('address')" rows="2" />
        <x-admin.form-select name="status" label="Status" :options="['active' => 'Aktif', 'inactive' => 'Nonaktif']" :value="old('status', 'active')" />
        <x-admin.form-input name="photo" label="Foto" type="file" accept="image/jpeg,image/png,image/webp" />
        <div class="mt-6 flex gap-3"><button type="submit" class="rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white">Simpan</button><a href="{{ route('admin.staff.index') }}" class="rounded-lg border bg-white px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a></div>
    </form>
</x-admin-layout>
