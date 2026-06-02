<x-admin-layout heading="Kalender Akademik">
    <div class="mb-6 flex items-center justify-between">
        <form method="GET" class="flex gap-2">
            <select name="type" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                <option value="">Semua Tipe</option>
                <option value="exam" @selected(request('type')=='exam')>Ujian</option>
                <option value="holiday" @selected(request('type')=='holiday')>Libur</option>
                <option value="event" @selected(request('type')=='event')>Event</option>
                <option value="registration" @selected(request('type')=='registration')>Pendaftaran</option>
                <option value="report" @selected(request('type')=='report')>Laporan</option>
                <option value="other" @selected(request('type')=='other')>Lainnya</option>
            </select>
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white">Filter</button>
        </form>
        @can('academic.create')
        <a href="{{ route('admin.academic-events.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Event
        </a>
        @endcan
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm"><thead class="border-b bg-slate-50"><tr>
            <th class="px-4 py-3 text-left font-semibold text-slate-700">Event</th><th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Tanggal</th><th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Tipe</th><th class="px-4 py-3 text-right font-semibold text-slate-700">Aksi</th>
        </tr></thead>
        <tbody class="divide-y">
            @forelse($events as $e)
            <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-medium">{{ $e->title }}</td>
                <td class="hidden md:table-cell px-4 py-3">{{ $e->start_date->format('d M Y') }}{{ $e->end_date ? ' - '.$e->end_date->format('d M Y') : '' }}</td>
                <td class="hidden md:table-cell px-4 py-3"><x-admin.badge :label="$e->type" variant="info" /></td>
                <td class="px-4 py-3 text-right">
                    @can('academic.update')<a href="{{ route('admin.academic-events.edit', $e) }}" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100">Edit</a>@endcan
                    @can('academic.delete')
                    <form method="POST" action="{{ route('admin.academic-events.destroy', $e) }}" class="inline" onsubmit="return confirm('Hapus event?')">@csrf @method('DELETE')<button type="submit" class="rounded-lg p-1.5 text-red-500 hover:bg-red-50">Hapus</button></form>
                    @endcan
                </td>
            </tr>
            @empty <tr><td colspan="4" class="px-4 py-16"><x-admin.empty-state title="Belum ada event" message="Tambahkan event kalender akademik." /></td></tr> @endforelse
        </tbody></table>
        @if($events->hasPages())<div class="px-4 py-3 border-t">{{ $events->links() }}</div>@endif
    </div>
</x-admin-layout>
