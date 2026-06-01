<x-admin-layout heading="Detail Jurusan: {{ $department->name }}">
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div><dt class="text-sm text-slate-500">Kode</dt><dd class="mt-1 font-mono text-sm">{{ $department->code }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Status</dt><dd class="mt-1"><x-admin.badge :label="$department->is_active ? 'Aktif' : 'Nonaktif'" :variant="$department->is_active ? 'success' : 'default'" /></dd></div>
                    <div class="sm:col-span-2"><dt class="text-sm text-slate-500">Deskripsi</dt><dd class="mt-1">{{ $department->description ?? '-' }}</dd></div>
                </dl>
            </div>
        </div>
        <div>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="font-semibold text-slate-900">Data Terkait</h3>
                <dl class="mt-3 space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-slate-500">Kompetensi</span><span class="font-medium">{{ $department->competencies_count }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Kelas</span><span class="font-medium">{{ $department->classrooms_count }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-500">Mata Pelajaran</span><span class="font-medium">{{ $department->subjects_count }}</span></div>
                </dl>
            </div>
            <div class="mt-4 flex flex-col gap-2">
                @can('academic.update')<a href="{{ route('admin.departments.edit', $department) }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-center text-sm font-medium text-slate-700">Edit</a>@endcan
                <a href="{{ route('admin.departments.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-center text-sm font-medium text-slate-700">Kembali</a>
            </div>
        </div>
    </div>
</x-admin-layout>
