<x-admin-layout heading="Edit Mitra Industri">
    <form method="POST" action="{{ route('admin.industry-partners.update', $partner) }}" class="max-w-xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf @method('PUT')
        <div class="space-y-5">
            <x-admin.form-input name="name" label="Nama Perusahaan" :value="old('name',$partner->name)" required />
            <x-admin.form-input name="sector" label="Sektor/Bidang" :value="old('sector',$partner->sector)" />
            <x-admin.form-input name="contact_person" label="Contact Person" :value="old('contact_person',$partner->contact_person)" />
            <x-admin.form-input name="phone" label="Telepon" :value="old('phone',$partner->phone)" />
            <x-admin.form-textarea name="address" label="Alamat" :value="old('address',$partner->address)" rows="3" />
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white">Simpan</button>
            <a href="{{ route('admin.industry-partners.index') }}" class="rounded-lg border px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a>
        </div>
    </form>
</x-admin-layout>