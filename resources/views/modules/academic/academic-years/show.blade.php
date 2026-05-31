<x-admin-layout heading="Detail Tahun Ajaran">
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-lg font-semibold text-slate-900">Informasi Tahun Ajaran</h2>
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div><dt class="text-sm text-slate-500">Nama</dt><dd class="mt-1 font-medium text-slate-900">{{ $academicYear->name }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Status</dt><dd class="mt-1"><x-admin.badge :label="$academicYear->is_active ? 'Aktif' : 'Nonaktif'" :variant="$academicYear->is_active ? 'success' : 'default'" /></dd></div>
                    <div><dt class="text-sm text-slate-500">Mulai</dt><dd class="mt-1 font-medium text-slate-900">{{ $academicYear->start_date->format('d M Y') }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Selesai</dt><dd class="mt-1 font-medium text-slate-900">{{ $academicYear->end_date->format('d M Y') }}</dd></div>
                </dl>
            </div>
            <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-lg font-semibold text-slate-900">Semester</h2>
                @if($academicYear->semesters->count() > 0)
                    <ul class="divide-y divide-slate-100">
                        @foreach($academicYear->semesters as $sem)
                            <li class="flex items-center justify-between py-2">
                                <span class="text-sm text-slate-700">{{ $sem->name }}</span>
                                <x-admin.badge :label="$sem->is_active ? 'Aktif' : 'Nonaktif'" :variant="$sem->is_active ? 'success' : 'default'" />
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-slate-500">Belum ada semester.</p>
                @endif
            </div>
        </div>
        <div class="flex flex-col gap-2">
            <a href="{{ route('admin.academic-years.edit', $academicYear) }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-center text-sm font-medium text-slate-700 transition hover:bg-slate-50">Edit</a>
            <a href="{{ route('admin.academic-years.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-center text-sm font-medium text-slate-700 transition hover:bg-slate-50">Kembali</a>
        </div>
    </div>
</x-admin-layout>