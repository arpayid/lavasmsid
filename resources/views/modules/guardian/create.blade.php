<x-admin-layout heading="Tambah Orang Tua/Wali">
    <form method="POST" action="{{ route('admin.guardians.store') }}" class="max-w-2xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">@csrf
        <x-admin.form-input name="name" label="Nama Lengkap" :value="old('name')" required />
        <x-admin.form-select name="relation" label="Hubungan" :options="['father' => 'Ayah', 'mother' => 'Ibu', 'guardian' => 'Wali', 'other' => 'Lainnya']" :value="old('relation', 'father')" />
        <x-admin.form-input name="phone" label="Telepon" :value="old('phone')" />
        <x-admin.form-input name="email" label="Email" type="email" :value="old('email')" />
        <x-admin.form-input name="occupation" label="Pekerjaan" :value="old('occupation')" />
        <x-admin.form-textarea name="address" label="Alamat" :value="old('address')" rows="2" />
        <div class="flex items-center gap-2 mt-4"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" id="is_active" class="rounded border-slate-300 text-primary-600" @checked(old('is_active', true))><label for="is_active" class="text-sm">Aktif</label></div>
        <div class="mt-6 flex gap-3"><button type="submit" class="rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white">Simpan</button><a href="{{ route('admin.guardians.index') }}" class="rounded-lg border bg-white px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a></div>
    </form>
</x-admin-layout>
