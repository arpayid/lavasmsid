<x-admin-layout heading="Detail Pendaftar: {{ $ppdb->candidate_name }}">
    <div class="grid gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-lg font-semibold">Data Calon Siswa</h2>
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div><dt class="text-sm text-slate-500">No. Pendaftaran</dt><dd class="font-mono text-sm">{{ $ppdb->registration_number }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Status</dt><dd><x-admin.badge :label="$ppdb->status" :variant="$ppdb->status=='accepted'?'success':($ppdb->status=='rejected'?'danger':($ppdb->status=='verified'?'info':'warning'))" /></dd></div>
                    <div><dt class="text-sm text-slate-500">Nama</dt><dd class="font-medium">{{ $ppdb->candidate_name }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Jurusan</dt><dd>{{ $ppdb->department->name ?? '-' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Email</dt><dd>{{ $ppdb->email ?? '-' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Telepon</dt><dd>{{ $ppdb->phone ?? '-' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Jenis Kelamin</dt><dd>{{ $ppdb->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Tempat/Tgl Lahir</dt><dd>{{ $ppdb->birth_place ?? '-' }}, {{ $ppdb->birth_date?->format('d M Y') ?? '-' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Asal Sekolah</dt><dd>{{ $ppdb->previous_school ?? '-' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Alamat</dt><dd class="col-span-2">{{ $ppdb->address ?? '-' }}</dd></div>
                </dl>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-lg font-semibold">Data Orang Tua</h2>
                <dl class="grid gap-4 sm:grid-cols-2">
                    <div><dt class="text-sm text-slate-500">Nama Orang Tua</dt><dd>{{ $ppdb->parent_name ?? '-' }}</dd></div>
                    <div><dt class="text-sm text-slate-500">Telepon</dt><dd>{{ $ppdb->parent_phone ?? '-' }}</dd></div>
                </dl>
            </div>
            @if($ppdb->notes)
            <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                <h2 class="mb-4 text-lg font-semibold">Catatan</h2>
                <p class="text-sm text-slate-700">{{ $ppdb->notes }}</p>
            </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="space-y-3">
            <a href="{{ route('admin.ppdb.index') }}" class="block rounded-lg border px-4 py-2.5 text-center text-sm font-medium text-slate-700">Kembali</a>

            @if($ppdb->status === 'submitted')
            <form method="POST" action="{{ route('admin.ppdb.verify', $ppdb) }}">
                @csrf
                <button type="submit" class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white">Verifikasi Berkas</button>
            </form>
            @endif

            @if($ppdb->status === 'verified')
            <form method="POST" action="{{ route('admin.ppdb.accept', $ppdb) }}">
                @csrf
                <button type="submit" class="w-full rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white">Terima</button>
            </form>
            <form method="POST" action="{{ route('admin.ppdb.reject', $ppdb) }}" onsubmit="return prompt('Alasan penolakan?')||true">
                @csrf
                <input type="hidden" name="notes" id="rejectNotes">
                <button type="submit" class="w-full rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white">Tolak</button>
            </form>
            @endif

            @if($ppdb->status === 'accepted')
            <form method="POST" action="{{ route('admin.ppdb.convert', $ppdb) }}" onsubmit="return confirm('Konversi pendaftar ini menjadi siswa aktif?')">
                @csrf
                <button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white">Konversi ke Siswa</button>
            </form>
            @endif
        </div>
    </div>
</x-admin-layout>