<x-admin-layout heading="Detail Staff">
    <div class="grid gap-6 lg:grid-cols-3"><div class="lg:col-span-2"><div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <dl class="grid gap-4 sm:grid-cols-2">
            <div><dt class="text-sm text-slate-500">NIK</dt><dd class="mt-1 font-mono">{{ $staff->employee_number ?? '-' }}</dd></div>
            <div><dt class="text-sm text-slate-500">Nama</dt><dd class="mt-1 font-medium">{{ $staff->name }}</dd></div>
            <div><dt class="text-sm text-slate-500">Jabatan</dt><dd class="mt-1">{{ $staff->position ?? '-' }}</dd></div>
            <div><dt class="text-sm text-slate-500">Jenis Kelamin</dt><dd class="mt-1">{{ $staff->gender == 'L' ? 'Laki-laki' : ($staff->gender == 'P' ? 'Perempuan' : '-') }}</dd></div>
            <div><dt class="text-sm text-slate-500">Email</dt><dd class="mt-1">{{ $staff->email ?? '-' }}</dd></div>
            <div><dt class="text-sm text-slate-500">Telepon</dt><dd class="mt-1">{{ $staff->phone ?? '-' }}</dd></div>
            <div><dt class="text-sm text-slate-500">Status</dt><dd class="mt-1"><x-admin.badge :label="$staff->status == 'active' ? 'Aktif' : 'Nonaktif'" :variant="$staff->status == 'active' ? 'success' : 'default'" /></dd></div>
            <div class="sm:col-span-2"><dt class="text-sm text-slate-500">Alamat</dt><dd class="mt-1">{{ $staff->address ?? '-' }}</dd></div>
        </dl>
    </div></div>
    <div class="space-y-4">
        @if($staff->photo_path)<div class="rounded-2xl bg-white p-6 shadow-sm text-center"><img src="{{ asset('storage/'.$staff->photo_path) }}" class="mx-auto h-32 w-32 rounded-xl object-cover"></div>@endif
        <div class="flex flex-col gap-2">@can('staff.update')<a href="{{ route('admin.staff.edit', $staff) }}" class="rounded-lg border bg-white px-4 py-2.5 text-center text-sm font-medium text-slate-700">Edit</a>@endcan<a href="{{ route('admin.staff.index') }}" class="rounded-lg border bg-white px-4 py-2.5 text-center text-sm font-medium text-slate-700">Kembali</a></div>
    </div></div>
</x-admin-layout>
