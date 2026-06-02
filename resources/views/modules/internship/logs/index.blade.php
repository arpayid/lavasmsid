<x-admin-layout heading="Logbook PKL">
    <div class="mb-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        <form method="GET" action="{{ route('admin.internships.logs.index') }}" class="flex gap-4">
            <x-admin.form-input name="internship_id" placeholder="Filter ID PKL" :value="request('internship_id')" />
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-indigo-700">Filter</button>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Tanggal</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Siswa</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Kegiatan</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                <th class="px-4 py-3 text-right font-semibold text-slate-700">Aksi</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($logs as $log)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $log->activity_date->format('d/m/Y') }}</td>
                    <td class="px-4 py-3 font-medium">{{ $log->internship->student->name ?? '-' }}</td>
                    <td class="px-4 py-3">{{ Str::limit($log->activity, 50) }}</td>
                    <td class="px-4 py-3">
                        @php $sv = ['submitted'=>'warning','reviewed'=>'success','rejected'=>'danger']; @endphp
                        <x-admin.badge :label="ucfirst($log->status)" :variant="$sv[$log->status]??'default'" />
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.internships.show', $log->internship_id) }}" class="text-indigo-600 hover:underline">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-16 text-center text-slate-500">Belum ada catatan kegiatan.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($logs->hasPages())<div class="px-4 py-3 border-t">{{ $logs->links() }}</div>@endif
    </div>
</x-admin-layout>
