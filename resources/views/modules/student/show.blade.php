<x-admin-layout heading="Detail Siswa: {{ $student->name }}">
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div><dt class="text-sm text-slate-500">NIS</dt><dd class="mt-1 font-medium">{{ $student->nis }}</dd></div>
                    <div><dt class="text-sm text-slate-500">NISN</dt><dd class="mt-1">{{ $student->nisn ?? '-' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Jenis Kelamin</dt><dd class="mt-1">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Tempat/Tgl Lahir</dt><dd class="mt-1">{{ $student->birth_place ?? '-' }}, {{ $student->birth_date?->format('d M Y') ?? '-' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Agama</dt><dd class="mt-1">{{ $student->religion ?? '-' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Telepon</dt><dd class="mt-1">{{ $student->phone ?? '-' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Kelas</dt><dd class="mt-1">{{ $student->classroom->name ?? '-' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Jurusan</dt><dd class="mt-1">{{ $student->department->name ?? '-' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Status</dt><dd class="mt-1">@php $sv=['active'=>'success','graduated'=>'info','moved'=>'warning','dropped'=>'danger']; $sl=['active'=>'Aktif','graduated'=>'Lulus','moved'=>'Pindah','dropped'=>'Keluar']; @endphp <x-admin.badge :label="$sl[$student->status]" :variant="$sv[$student->status]" /></dd></div>
                    <div class="sm:col-span-2"><dt class="text-sm text-slate-500">Alamat</dt><dd class="mt-1">{{ $student->address ?? '-' }}</dd></div>
                </dl>
            </div>
        </div>
        <div class="space-y-4">
            @if($student->photo_path)
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 text-center">
                <img src="{{ asset('storage/' . $student->photo_path) }}" class="mx-auto h-32 w-32 rounded-xl object-cover">
            </div>
            @endif
            <div class="flex flex-col gap-2">
                @can('student.update')<a href="{{ route('admin.students.edit', $student) }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-center text-sm font-medium text-slate-700">Edit</a>@endcan
                <a href="{{ route('admin.students.index') }}" class="rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-center text-sm font-medium text-slate-700">Kembali</a>
            </div>
        </div>
    </div>
</x-admin-layout>
