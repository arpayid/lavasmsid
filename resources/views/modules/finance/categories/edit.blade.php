<x-admin-layout heading="Edit Kategori">
    <form method="POST" action="{{ route('admin.finance.categories.update', $category) }}" class="max-w-xl rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf @method('PUT')
        <div class="space-y-5">
            <x-admin.form-input name="name" label="Nama Kategori" :value="old('name',$category->name)" required />
            <x-admin.form-textarea name="description" label="Deskripsi" :value="old('description',$category->description)" rows="3" />
        </div>
        <div class="mt-6 flex gap-3">
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white">Simpan</button>
            <a href="{{ route('admin.finance.categories.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700">Batal</a>
        </div>
    </form>
</x-admin-layout>