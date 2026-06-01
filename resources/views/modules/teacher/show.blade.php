<x-admin-layout heading="Detail Guru: {{ $teacher->name }}">
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div><dt class="text-sm text-slate-500">NIP</dt><dd class="mt-1 font-mono">{{ $teacher->nip ?? '-' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">NUPTK</dt><dd class="mt-1 font-mono">{{ $teacher->nuptk ?? '-' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Nama</dt><dd class="mt-1 font-medium">{{ $teacher->name }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Jenis Kelamin</dt><dd class="mt-1">{{ $teacher->gender == 'L' ? 'Laki-laki' : ($teacher->gender == 'P' ? 'Perempuan' : '-') }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Tempat/Tgl Lahir</dt><dd class="mt-1">{{ $teacher->birth_place ?? '-' }}, {{ $teacher->birth_date?->format('d M Y') ?? '-' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Email</dt><dd class="mt-1">{{ $teacher->email ?? '-' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Telepon</dt><dd class="mt-1">{{ $teacher->phone ?? '-' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Pendidikan</dt><dd class="mt-1">{{ $teacher->qualification ?? '-' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Status</dt><dd class="mt-1"><x-admin.badge :label="$teacher->status == 'active' ? 'Aktif' : 'Nonaktif'" :variant="$teacher->status == 'active' ? 'success' : 'default'" /></dd></div>
                    <div class="sm:col-span-2"><dt class="text-sm text-slate-500">Alamat</dt><dd class="mt-1">{{ $teacher->address ?? '-' }}</dd></div>
                </dl>
            </div>
            @if($teacher->subjects->count() > 0)
            <div class="mt-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h3 class="font-semibold text-slate-900 mb-3">Mata Pelajaran yang Diajar</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($teacher->subjects as $subj)
                    <x-admin.badge :label="$subj->name" variant="info" />
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        <div class="space-y-4">
            @if($teacher->photo_path)
            <div class="rounded-2xl bg-white p-6 shadow-sm text-center"><img src="{{ asset('storage/'.$teacher->photo_path) }}" class="mx-auto h-32 w-32 rounded-xl object-cover"></div>
            @endif
            <div class="flex flex-col gap-2">
                @can('teacher.update')<a href="{{ route('admin.teachers.edit', $teacher) }}" class="rounded-lg border bg-white px-4 py-2.5 text-center text-sm font-medium text-slate-700">Edit</a>@endcan
                <a href="{{ route('admin.teachers.index') }}" class="rounded-lg border bg-white px-4 py-2.5 text-center text-sm font-medium text-slate-700">Kembali</a>
            </div>
        </div>
    </div>
</x-admin-layout>
