<x-admin-layout heading="Laporan PPDB">
    <div class="mb-6 grid gap-4 sm:grid-cols-5">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 text-center"><div class="text-2xl font-bold text-slate-900">{{ $summary['total'] }}</div><div class="text-xs text-slate-500">Total</div></div>
        <div class="rounded-xl bg-amber-50 p-4 text-center"><div class="text-2xl font-bold text-amber-700">{{ $summary['submitted'] }}</div><div class="text-xs text-amber-600">Baru</div></div>
        <div class="rounded-xl bg-blue-50 p-4 text-center"><div class="text-2xl font-bold text-blue-700">{{ $summary['verified'] }}</div><div class="text-xs text-blue-600">Terverifikasi</div></div>
        <div class="rounded-xl bg-emerald-50 p-4 text-center"><div class="text-2xl font-bold text-emerald-700">{{ $summary['accepted'] }}</div><div class="text-xs text-emerald-600">Diterima</div></div>
        <div class="rounded-xl bg-red-50 p-4 text-center"><div class="text-2xl font-bold text-red-700">{{ $summary['rejected'] }}</div><div class="text-xs text-red-600">Ditolak</div></div>
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">No. Daftar</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Nama</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Jurusan</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($registrations as $r)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $r->registration_number }}</td>
                    <td class="px-4 py-3 font-medium">{{ $r->candidate_name }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $r->department->name ?? '-' }}</td>
                    <td class="px-4 py-3"><x-admin.badge :label="$r->status" :variant="$r->status=='accepted'?'success':($r->status=='rejected'?'danger':'warning')" /></td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-16 text-center text-slate-500">Tidak ada data PPDB.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
