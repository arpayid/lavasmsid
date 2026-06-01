<x-admin-layout heading="Tambah Event Kalender">
    <form method="POST" action="{{ route('admin.academic-events.store') }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">@csrf
        <x-admin.form-input name="title" label="Judul" :value="old('title')" required />
        <x-admin.form-textarea name="description" label="Deskripsi" :value="old('description')" rows="3" />
        <div class="grid gap-4 md:grid-cols-2">
            <x-admin.form-input name="start_date" label="Tanggal Mulai" type="date" :value="old('start_date')" required />
            <x-admin.form-input name="end_date" label="Tanggal Selesai" type="date" :value="old('end_date')" />
        </div>
        <x-admin.form-select name="type" label="Tipe" :options="['exam'=>'Ujian','holiday'=>'Libur','event'=>'Event','registration'=>'Pendaftaran','report'=>'Laporan','other'=>'Lainnya']" :value="old('type','event')" />
        <div class="flex items-center gap-2 mt-4"><input type="hidden" name="is_public" value="0"><input type="checkbox" name="is_public" value="1" id="is_public" class="rounded border-slate-300 text-primary-600" @checked(old('is_public', true))><label for="is_public" class="text-sm">Publik</label></div>
        <div class="mt-6 flex gap-3"><button type="submit" class="rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white">Simpan</button><a href="{{ route('admin.academic-events.index') }}" class="rounded-lg border bg-white px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a></div>
    </form>
</x-admin-layout>
