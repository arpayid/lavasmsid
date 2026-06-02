<x-admin-layout heading="Edit Pengumuman">
    <form method="POST" action="{{ route('admin.communication.announcements.update', $announcement) }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf @method('PUT')
        <div class="space-y-5">
            <x-admin.form-input name="title" label="Judul Pengumuman" :value="old('title', $announcement->title)" required />
            <x-admin.form-textarea name="content" label="Konten" :value="old('content', $announcement->content)" rows="8" required />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-admin.form-select name="target" label="Target Penerima" :options="['all'=>'Semua','students'=>'Siswa','teachers'=>'Guru','parents'=>'Orang Tua','staff'=>'Staff']" :value="old('target', $announcement->target)" required />
                <x-admin.form-select name="priority" label="Prioritas" :options="['low'=>'Rendah','normal'=>'Normal','high'=>'Tinggi','urgent'=>'Urgent']" :value="old('priority', $announcement->priority)" required />
            </div>
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" id="is_published" class="rounded border-slate-300 text-indigo-600" @checked(old('is_published', $announcement->is_published))>
                <label for="is_published" class="text-sm text-slate-700 font-medium">Terbitkan</label>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-6 py-2.5 text-sm font-bold text-white transition hover:bg-indigo-700 shadow-md shadow-indigo-200">Simpan Perubahan</button>
            <a href="{{ route('admin.communication.announcements.index') }}" class="rounded-lg border border-slate-300 bg-white px-6 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">Batal</a>
        </div>
    </form>
</x-admin-layout>
