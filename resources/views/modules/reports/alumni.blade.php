<x-admin-layout heading="Laporan Alumni">
    <div class="mb-6 grid gap-4 sm:grid-cols-4">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-slate-200 text-center"><div class="text-2xl font-bold text-slate-900">{{ $summary['total'] }}</div><div class="text-xs text-slate-500">Total Alumni</div></div>
        <div class="rounded-xl bg-emerald-50 p-4 text-center"><div class="text-2xl font-bold text-emerald-700">{{ $summary['working'] }}</div><div class="text-xs text-emerald-600">Bekerja</div></div>
        <div class="rounded-xl bg-blue-50 p-4 text-center"><div class="text-2xl font-bold text-blue-700">{{ $summary['studying'] }}</div><div class="text-xs text-blue-600">Kuliah</div></div>
        <div class="rounded-xl bg-amber-50 p-4 text-center"><div class="text-2xl font-bold text-amber-700">{{ $summary['entrepreneur'] }}</div><div class="text-xs text-amber-600">Wirausaha</div></div>
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Nama</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Jurusan</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Tahun</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Perusahaan/Institusi</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($alumni as $a)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium">{{ $a->name }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $a->department->name ?? '-' }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $a->graduation_year }}</td>
                    <td class="px-4 py-3"><x-admin.badge :label="$a->status" :variant="$a->status=='working'?'success':($a->status=='studying'?'info':'warning')" /></td>
                    <td class="hidden md:table-cell px-4 py-3 text-xs">{{ $a->company_name ?? $a->institution_name ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-16 text-center text-slate-500">Tidak ada data alumni.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
