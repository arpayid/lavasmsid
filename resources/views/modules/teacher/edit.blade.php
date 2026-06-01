<x-admin-layout heading="Edit Guru">
    <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}" enctype="multipart/form-data" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf @method('PUT')
        <x-admin.form-input name="name" label="Nama Lengkap" :value="old('name', $teacher->name)" required />
        <div class="grid gap-4 md:grid-cols-2">
            <x-admin.form-input name="nip" label="NIP" :value="old('nip', $teacher->nip)" />
            <x-admin.form-input name="nuptk" label="NUPTK" :value="old('nuptk', $teacher->nuptk)" />
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            <x-admin.form-select name="gender" label="Jenis Kelamin" :options="['L' => 'Laki-laki', 'P' => 'Perempuan']" :value="old('gender', $teacher->gender)" />
            <x-admin.form-input name="birth_place" label="Tempat Lahir" :value="old('birth_place', $teacher->birth_place)" />
        </div>
        <x-admin.form-input name="birth_date" label="Tanggal Lahir" type="date" :value="old('birth_date', $teacher->birth_date?->format('Y-m-d'))" />
        <x-admin.form-input name="email" label="Email" type="email" :value="old('email', $teacher->email)" />
        <x-admin.form-input name="phone" label="Telepon" :value="old('phone', $teacher->phone)" />
        <x-admin.form-textarea name="address" label="Alamat" :value="old('address', $teacher->address)" rows="2" />
        <div class="grid gap-4 md:grid-cols-2">
            <x-admin.form-input name="qualification" label="Pendidikan" :value="old('qualification', $teacher->qualification)" />
            <x-admin.form-input name="certification_number" label="No. Sertifikasi" :value="old('certification_number', $teacher->certification_number)" />
        </div>
        <x-admin.form-select name="status" label="Status" :options="['active' => 'Aktif', 'inactive' => 'Nonaktif']" :value="old('status', $teacher->status)" />
        @if($teacher->photo_path)<img src="{{ asset('storage/' . $teacher->photo_path) }}" class="h-16 w-16 rounded-lg object-cover mb-3">@endif
        <x-admin.form-input name="photo" label="Ganti Foto" type="file" accept="image/jpeg,image/png,image/webp" />
        <div class="mt-6 flex gap-3">
            <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white">Simpan</button>
            <a href="{{ route('admin.teachers.index') }}" class="rounded-lg border bg-white px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a>
        </div>
    </form>
</x-admin-layout>
