<x-admin-layout heading="Edit Ruang Kelas">
    <form method="POST" action="{{ route('admin.classrooms.update', $classroom) }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf @method('PUT')
        <div class="space-y-5">
            <x-admin.form-select name="department_id" label="Jurusan" :options="$departments->pluck('name', 'id')->toArray()" :value="old('department_id', $classroom->department_id)" placeholder="Tidak ada" />
            <x-admin.form-input name="name" label="Nama Kelas" :value="old('name', $classroom->name)" required />
            <x-admin.form-input name="level" label="Tingkat" :value="old('level', $classroom->level)" required />
            <x-admin.form-input name="room" label="Ruang" :value="old('room', $classroom->room)" />
        </div>
        <div class="mt-6 flex items-center gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">Simpan</button>
            <a href="{{ route('admin.classrooms.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Batal</a>
        </div>
    </form>
</x-admin-layout>
