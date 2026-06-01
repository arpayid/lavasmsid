<x-admin-layout heading="Rapor: {{ $student->name }}">
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 mb-6">
        <dl class="grid gap-3 sm:grid-cols-2 text-sm">
            <div><dt class="text-slate-500">Nama</dt><dd class="font-medium">{{ $student->name }}</dd></div>
            <div><dt class="text-slate-500">NIS</dt><dd>{{ $student->nis }}</dd></div>
            <div><dt class="text-slate-500">Kelas</dt><dd>{{ $student->classroom->name ?? '-' }}</dd></div>
            <div><dt class="text-slate-500">Jurusan</dt><dd>{{ $student->department->name ?? '-' }}</dd></div>
        </dl>
    </div>
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200 mb-6">
        <h3 class="font-semibold text-slate-900 mb-4">Nilai</h3>
        <table class="w-full text-sm"><thead class="border-b bg-slate-50"><tr>
            <th class="px-4 py-2 text-left">Mapel</th><th class="px-4 py-2 text-center">Tugas</th><th class="px-4 py-2 text-center">UTS</th><th class="px-4 py-2 text-center">UAS</th><th class="px-4 py-2 text-center">Praktik</th><th class="px-4 py-2 text-center">Akhir</th><th class="px-4 py-2 text-center">Huruf</th>
        </tr></thead>
        <tbody class="divide-y">
            @forelse($grades as $g)
            <tr class="hover:bg-slate-50">
                <td class="px-4 py-2">{{ $g->subject->name ?? '-' }}</td>
                <td class="px-4 py-2 text-center">{{ $g->assignment_score ?? '-' }}</td>
                <td class="px-4 py-2 text-center">{{ $g->midterm_score ?? '-' }}</td>
                <td class="px-4 py-2 text-center">{{ $g->final_score ?? '-' }}</td>
                <td class="px-4 py-2 text-center">{{ $g->practice_score ?? '-' }}</td>
                <td class="px-4 py-2 text-center font-semibold">{{ $g->final_result }}</td>
                <td class="px-4 py-2 text-center"><x-admin.badge :label="$g->grade_letter ?? '-'" variant="info" /></td>
            </tr>
            @empty <tr><td colspan="7" class="px-4 py-8 text-center text-slate-500">Belum ada nilai.</td></tr> @endforelse
        </tbody></table>
    </div>
    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <h3 class="font-semibold text-slate-900 mb-4">Rekap Absensi</h3>
        <div class="grid gap-4 sm:grid-cols-5">
            <div class="rounded-lg bg-emerald-50 p-3 text-center"><div class="text-xl font-bold text-emerald-700">{{ $attendanceSummary['present'] }}</div><div class="text-xs text-emerald-600">Hadir</div></div>
            <div class="rounded-lg bg-amber-50 p-3 text-center"><div class="text-xl font-bold text-amber-700">{{ $attendanceSummary['sick'] }}</div><div class="text-xs text-amber-600">Sakit</div></div>
            <div class="rounded-lg bg-blue-50 p-3 text-center"><div class="text-xl font-bold text-blue-700">{{ $attendanceSummary['permission'] }}</div><div class="text-xs text-blue-600">Izin</div></div>
            <div class="rounded-lg bg-red-50 p-3 text-center"><div class="text-xl font-bold text-red-700">{{ $attendanceSummary['absent'] }}</div><div class="text-xs text-red-600">Alpha</div></div>
            <div class="rounded-lg bg-orange-50 p-3 text-center"><div class="text-xl font-bold text-orange-700">{{ $attendanceSummary['late'] }}</div><div class="text-xs text-orange-600">Terlambat</div></div>
        </div>
    </div>
</x-admin-layout>
