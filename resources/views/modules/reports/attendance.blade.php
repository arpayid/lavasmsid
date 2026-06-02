<x-admin-layout heading="Laporan Absensi">
    {{-- Summary --}}
    <div class="mb-6 grid gap-4 sm:grid-cols-5">
        <div class="rounded-xl bg-emerald-50 p-4 text-center"><div class="text-2xl font-bold text-emerald-700">{{ $summary['present'] }}</div><div class="text-xs text-emerald-600">Hadir</div></div>
        <div class="rounded-xl bg-amber-50 p-4 text-center"><div class="text-2xl font-bold text-amber-700">{{ $summary['sick'] }}</div><div class="text-xs text-amber-600">Sakit</div></div>
        <div class="rounded-xl bg-blue-50 p-4 text-center"><div class="text-2xl font-bold text-blue-700">{{ $summary['permission'] }}</div><div class="text-xs text-blue-600">Izin</div></div>
        <div class="rounded-xl bg-red-50 p-4 text-center"><div class="text-2xl font-bold text-red-700">{{ $summary['absent'] }}</div><div class="text-xs text-red-600">Alpha</div></div>
        <div class="rounded-xl bg-orange-50 p-4 text-center"><div class="text-2xl font-bold text-orange-700">{{ $summary['late'] }}</div><div class="text-xs text-orange-600">Terlambat</div></div>
    </div>
    <div class="mb-6">
        <form method="GET" class="flex gap-2">
            <select name="classroom_id" class="rounded-lg border px-3 py-2 text-sm">
                <option value="">Semua Kelas</option>
                @foreach($classrooms as $c)
                <option value="{{ $c->id }}" @selected(request('classroom_id')==$c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">Filter</button>
            @if(Route::has('admin.reports.attendance.export'))
            <a href="{{ route('admin.reports.attendance.export', request()->query()) }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Export CSV</a>
            @endif
        </form>
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Tanggal</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Siswa</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Status</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($attendances as $a)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3">{{ $a->attendance_date->format('d M Y') }}</td>
                    <td class="px-4 py-3 font-medium">{{ $a->student->name ?? '-' }}</td>
                    <td class="hidden md:table-cell px-4 py-3"><x-admin.badge :label="$a->status" :variant="$a->status=='present'?'success':($a->status=='absent'?'danger':'warning')" /></td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-4 py-16 text-center text-slate-500">Tidak ada data absensi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
