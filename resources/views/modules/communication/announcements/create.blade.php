<x-admin-layout heading="Buat Pengumuman">
    <form method="POST" action="{{ route('admin.communication.announcements.store') }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        <div class="space-y-5">
            <x-admin.form-input name="title" label="Judul Pengumuman" :value="old('title')" required />
            <x-admin.form-textarea name="content" label="Konten" :value="old('content')" rows="8" required />
            <x-admin.form-select name="target" label="Target Penerima" :options="['all'=>'Semua','students'=>'Siswa','teachers'=>'Guru','parents'=>'Orang Tua','staff'=>'Staff']" :value="old('target','all')" required />
            <x-admin.form-select name="priority" label="Prioritas" :options="['low'=>'Rendah','normal'=>'Normal','high'=>'Tinggi','urgent'=>'Urgent']" :value="old('priority','normal')" />
            <div class="flex items-center gap-2">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" id="is_published" class="rounded border-slate-300 text-indigo-600" @checked(old('is_published', true))>
                <label for="is_published" class="text-sm text-slate-700">Terbitkan Sekarang</label>
            </div>
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white">Terbitkan</button>
            <a href="{{ route('admin.communication.announcements.index') }}" class="rounded-lg border px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a>
        </div>
    </form>
</x-admin-layout>
