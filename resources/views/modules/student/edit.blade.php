<x-admin-layout heading="Edit Siswa">
    <form method="POST" action="{{ route('admin.students.update', $student) }}" enctype="multipart/form-data" class="max-w-3xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf @method('PUT')
        <div class="grid gap-5 md:grid-cols-2">
            <x-admin.form-input name="nis" label="NIS" :value="old('nis', $student->nis)" required />
            <x-admin.form-input name="nisn" label="NISN" :value="old('nisn', $student->nisn)" />
        </div>
        <x-admin.form-input name="name" label="Nama Lengkap" :value="old('name', $student->name)" required />
        <div class="grid gap-5 md:grid-cols-2">
            <x-admin.form-select name="gender" label="Jenis Kelamin" :options="['L' => 'Laki-laki', 'P' => 'Perempuan']" :value="old('gender', $student->gender)" required />
            <x-admin.form-input name="birth_date" label="Tanggal Lahir" type="date" :value="old('birth_date', $student->birth_date?->format('Y-m-d'))" />
        </div>
        <x-admin.form-input name="birth_place" label="Tempat Lahir" :value="old('birth_place', $student->birth_place)" />
        <x-admin.form-input name="religion" label="Agama" :value="old('religion', $student->religion)" />
        <x-admin.form-input name="phone" label="No. Telepon" :value="old('phone', $student->phone)" />
        <x-admin.form-textarea name="address" label="Alamat" :value="old('address', $student->address)" rows="2" />
        <div class="grid gap-5 md:grid-cols-2">
            <x-admin.form-select name="department_id" label="Jurusan" :options="$departments->pluck('name','id')->toArray()" :value="old('department_id', $student->department_id)" placeholder="-- Pilih --" />
            <x-admin.form-select name="classroom_id" label="Kelas" :options="$classrooms->pluck('name','id')->toArray()" :value="old('classroom_id', $student->classroom_id)" placeholder="-- Pilih --" />
        </div>
        <x-admin.form-select name="status" label="Status" :options="['active' => 'Aktif', 'graduated' => 'Lulus', 'moved' => 'Pindah', 'dropped' => 'Keluar']" :value="old('status', $student->status)" />
        @if($student->photo_path)
        <div class="mb-4"><img src="{{ asset('storage/' . $student->photo_path) }}" class="h-20 w-20 rounded-lg object-cover"></div>
        @endif
        <x-admin.form-input name="photo" label="Ganti Foto" type="file" accept="image/jpeg,image/png,image/webp" />
        <div class="mt-6 flex gap-3">
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white">Simpan</button>
            <a href="{{ route('admin.students.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a>
        </div>
    </form>
</x-admin-layout>
