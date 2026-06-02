<x-admin-layout heading="Laporan Kelas">
    <div class="mb-6 flex justify-end">
        @if(Route::has('admin.reports.classrooms.export'))
        <a href="{{ route('admin.reports.classrooms.export') }}" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white hover:bg-emerald-700">Export CSV</a>
        @endif
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Kelas</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Jurusan</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Tingkat</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($classrooms as $c)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-medium">{{ $c->name }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $c->department->name ?? '-' }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $c->level }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-4 py-16 text-center text-slate-500">Tidak ada data kelas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
