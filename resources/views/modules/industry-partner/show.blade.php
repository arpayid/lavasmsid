<x-admin-layout heading="Detail Mitra Industri">
    <div class="max-w-3xl space-y-6">
        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <dl class="grid gap-4 sm:grid-cols-2">
                <div><dt class="text-sm text-slate-500">Nama Mitra</dt><dd class="font-medium">{{ $partner->name }}</dd></div>
                <div><dt class="text-sm text-slate-500">Sektor</dt><dd>{{ $partner->sector ?? '-' }}</dd></div>
                <div><dt class="text-sm text-slate-500">Kontak Person</dt><dd>{{ $partner->contact_person ?? '-' }}</dd></div>
                <div><dt class="text-sm text-slate-500">Telepon</dt><dd>{{ $partner->phone ?? '-' }}</dd></div>
                <div class="sm:col-span-2"><dt class="text-sm text-slate-500">Alamat</dt><dd>{{ $partner->address ?? '-' }}</dd></div>
            </dl>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h3 class="mb-4 font-semibold">Riwayat PKL</h3>
            @if($partner->internships->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b"><tr><th class="px-3 py-2 text-left font-semibold">Siswa</th><th class="px-3 py-2 text-left font-semibold">Status</th><th class="px-3 py-2 text-left font-semibold">Periode</th></tr></thead>
                    <tbody class="divide-y">
                        @foreach($partner->internships as $i)
                        <tr>
                            <td class="px-3 py-2">{{ $i->student->name ?? '-' }}</td>
                            <td class="px-3 py-2"><x-admin.badge :label="ucfirst($i->status)" :variant="$i->status=='completed'?'success':($i->status=='ongoing'?'primary':'warning')" /></td>
                            <td class="px-3 py-2 text-xs">{{ $i->start_date->format('d/m/Y') }} - {{ $i->end_date->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <p class="text-sm text-slate-500">Belum ada data PKL untuk mitra ini.</p>
            @endif
        </div>

        <div class="flex gap-3">
            @can('industry.update')<a href="{{ route('admin.industry-partners.edit', $partner) }}" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white">Edit</a>@endcan
            <a href="{{ route('admin.industry-partners.index') }}" class="rounded-lg border px-4 py-2.5 text-sm font-medium text-slate-700">Kembali</a>
        </div>
    </div>
</x-admin-layout>
