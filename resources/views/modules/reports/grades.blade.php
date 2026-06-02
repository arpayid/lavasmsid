<x-admin-layout heading="Laporan Nilai">
    <div class="mb-6 flex gap-2">
        <form method="GET" class="flex gap-2">
            <select name="semester_id" class="rounded-lg border px-3 py-2 text-sm">
                <option value="">Semua Semester</option>
                @foreach($semesters as $s)
                <option value="{{ $s->id }}" @selected(request('semester_id')==$s->id)>{{ $s->name }} ({{ $s->academicYear->name }})</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white">Filter</button>
            @if(Route::has('admin.reports.grades.export'))
            <a href="{{ route('admin.reports.grades.export', request()->query()) }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Export CSV</a>
            @endif
        </form>
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Siswa</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Mapel</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Semester</th>
                <th class="hidden md:table-cell px-4 py-3 text-right font-semibold text-slate-700">Tugas</th>
                <th class="hidden md:table-cell px-4 py-3 text-right font-semibold text-slate-700">UTS</th>
                <th class="hidden md:table-cell px-4 py-3 text-right font-semibold text-slate-700">UAS</th>
                <th class="px-4 py-3 text-right font-semibold text-slate-700">Hasil</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($grades as $g)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium">{{ $g->student->name ?? '-' }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $g->subject->name ?? '-' }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $g->semester->name ?? '-' }}</td>
                    <td class="hidden md:table-cell px-4 py-3 text-right">{{ $g->assignment_score }}</td>
                    <td class="hidden md:table-cell px-4 py-3 text-right">{{ $g->midterm_score }}</td>
                    <td class="hidden md:table-cell px-4 py-3 text-right">{{ $g->final_score }}</td>
                    <td class="px-4 py-3 text-right"><x-admin.badge :label="number_format($g->final_result,1)" :variant="$g->final_result>=75?'success':($g->final_result>=60?'warning':'danger')" /></td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-16 text-center text-slate-500">Tidak ada data nilai.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
