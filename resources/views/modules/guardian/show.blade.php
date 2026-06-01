<x-admin-layout heading="Detail Orang Tua/Wali">
    <div class="grid gap-6 lg:grid-cols-3"><div class="lg:col-span-2"><div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <dl class="grid gap-4 sm:grid-cols-2">
            <div><dt class="text-sm text-slate-500">Nama</dt><dd class="mt-1 font-medium">{{ $guardian->name }}</dd></div>
            <div><dt class="text-sm text-slate-500">Hubungan</dt><dd class="mt-1 capitalize">{{ $guardian->relation }}</dd></div>
            <div><dt class="text-sm text-slate-500">Telepon</dt><dd class="mt-1">{{ $guardian->phone ?? '-' }}</dd></div>
            <div><dt class="text-sm text-slate-500">Email</dt><dd class="mt-1">{{ $guardian->email ?? '-' }}</dd></div>
            <div><dt class="text-sm text-slate-500">Pekerjaan</dt><dd class="mt-1">{{ $guardian->occupation ?? '-' }}</dd></div>
            <div><dt class="text-sm text-slate-500">Status</dt><dd class="mt-1"><x-admin.badge :label="$guardian->is_active ? 'Aktif' : 'Nonaktif'" :variant="$guardian->is_active ? 'success' : 'default'" /></dd></div>
            <div class="sm:col-span-2"><dt class="text-sm text-slate-500">Alamat</dt><dd class="mt-1">{{ $guardian->address ?? '-' }}</dd></div>
        </dl>
    </div></div>
    <div class="flex flex-col gap-2">@can('guardian.update')<a href="{{ route('admin.guardians.edit', $guardian) }}" class="rounded-lg border bg-white px-4 py-2.5 text-center text-sm font-medium text-slate-700">Edit</a>@endcan<a href="{{ route('admin.guardians.index') }}" class="rounded-lg border bg-white px-4 py-2.5 text-center text-sm font-medium text-slate-700">Kembali</a></div></div>
</x-admin-layout>
