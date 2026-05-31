<x-admin-layout heading="Laporan PKL">
    <div class="mb-6 grid gap-4 sm:grid-cols-4">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 text-center"><div class="text-2xl font-bold text-slate-900">{{ $summary['total'] }}</div><div class="text-xs text-slate-500">Total</div></div>
        <div class="rounded-xl bg-amber-50 p-4 text-center"><div class="text-2xl font-bold text-amber-700">{{ $summary['planned'] }}</div><div class="text-xs text-amber-600">Direncanakan</div></div>
        <div class="rounded-xl bg-blue-50 p-4 text-center"><div class="text-2xl font-bold text-blue-700">{{ $summary['ongoing'] }}</div><div class="text-xs text-blue-600">Berlangsung</div></div>
        <div class="rounded-xl bg-emerald-50 p-4 text-center"><div class="text-2xl font-bold text-emerald-700">{{ $summary['completed'] }}</div><div class="text-xs text-emerald-600">Selesai</div></div>
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Siswa</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Mitra</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Periode</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($internships as $i)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium">{{ $i->student->name ?? '-' }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $i->industryPartner->name ?? '-' }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $i->start_date->format('d M Y') }} - {{ $i->end_date->format('d M Y') }}</td>
                    <td class="px-4 py-3"><x-admin.badge :label="$i->status" :variant="$i->status=='completed'?'success':($i->status=='ongoing'?'info':'warning')" /></td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-16 text-center text-slate-500">Tidak ada data PKL.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
