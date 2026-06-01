<x-admin-layout heading="Tambah Siswa">
    <form method="POST" action="{{ route('admin.students.store') }}" enctype="multipart/form-data" class="max-w-3xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        <div class="grid gap-5 md:grid-cols-2">
            <x-admin.form-input name="nis" label="NIS" :value="old('nis')" required />
            <x-admin.form-input name="nisn" label="NISN" :value="old('nisn')" />
        </div>
        <x-admin.form-input name="name" label="Nama Lengkap" :value="old('name')" required />
        <div class="grid gap-5 md:grid-cols-2">
            <x-admin.form-select name="gender" label="Jenis Kelamin" :options="['L' => 'Laki-laki', 'P' => 'Perempuan']" :value="old('gender')" required />
            <x-admin.form-input name="birth_date" label="Tanggal Lahir" type="date" :value="old('birth_date')" />
        </div>
        <x-admin.form-input name="birth_place" label="Tempat Lahir" :value="old('birth_place')" />
        <x-admin.form-input name="religion" label="Agama" :value="old('religion')" />
        <x-admin.form-input name="phone" label="No. Telepon" :value="old('phone')" />
        <x-admin.form-textarea name="address" label="Alamat" :value="old('address')" rows="2" />
        <div class="grid gap-5 md:grid-cols-2">
            <x-admin.form-select name="department_id" label="Jurusan" :options="$departments->pluck('name','id')->toArray()" :value="old('department_id')" placeholder="-- Pilih --" />
            <x-admin.form-select name="classroom_id" label="Kelas" :options="$classrooms->pluck('name','id')->toArray()" :value="old('classroom_id')" placeholder="-- Pilih --" />
        </div>
        <x-admin.form-select name="status" label="Status" :options="['active' => 'Aktif', 'graduated' => 'Lulus', 'moved' => 'Pindah', 'dropped' => 'Keluar']" :value="old('status', 'active')" />
        <x-admin.form-input name="photo" label="Foto" type="file" accept="image/jpeg,image/png,image/webp" />
        <div class="mt-6 flex gap-3">
            <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white">Simpan</button>
            <a href="{{ route('admin.students.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a>
        </div>
    </form>
</x-admin-layout>
