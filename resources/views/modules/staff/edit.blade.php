<x-admin-layout heading="Edit Staff">
    <form method="POST" action="{{ route('admin.staff.update', $staff) }}" enctype="multipart/form-data" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">@csrf @method('PUT')
        <x-admin.form-input name="employee_number" label="NIK" :value="old('employee_number', $staff->employee_number)" />
        <x-admin.form-input name="name" label="Nama Lengkap" :value="old('name', $staff->name)" required />
        <x-admin.form-input name="position" label="Jabatan" :value="old('position', $staff->position)" />
        <x-admin.form-select name="gender" label="Jenis Kelamin" :options="['L' => 'Laki-laki', 'P' => 'Perempuan']" :value="old('gender', $staff->gender)" />
        <x-admin.form-input name="email" label="Email" type="email" :value="old('email', $staff->email)" />
        <x-admin.form-input name="phone" label="Telepon" :value="old('phone', $staff->phone)" />
        <x-admin.form-textarea name="address" label="Alamat" :value="old('address', $staff->address)" rows="2" />
        <x-admin.form-select name="status" label="Status" :options="['active' => 'Aktif', 'inactive' => 'Nonaktif']" :value="old('status', $staff->status)" />
        @if($staff->photo_path)<img src="{{ asset('storage/' . $staff->photo_path) }}" class="h-16 w-16 rounded-lg object-cover mb-3">@endif
        <x-admin.form-input name="photo" label="Ganti Foto" type="file" accept="image/jpeg,image/png,image/webp" />
        <div class="mt-6 flex gap-3"><button type="submit" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white">Simpan</button><a href="{{ route('admin.staff.index') }}" class="rounded-lg border bg-white px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a></div>
    </form>
</x-admin-layout>
