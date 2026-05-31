<x-admin-layout heading="Absensi">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('admin.attendances.index') }}" class="flex gap-2 flex-wrap">
            <select name="classroom_id" class="rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm">
                <option value="">Semua Kelas</option>
                @foreach($classrooms as $c)
                    <option value="{{ $c->id }}" @selected(request('classroom_id') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
            <input type="date" name="date" value="{{ request('date') }}" class="rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm">
            <select name="status" class="rounded-lg border border-slate-300 bg-white px-3 py-2.5 text-sm">
                <option value="">Semua Status</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') == $key)>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">Filter</button>
        </form>
        <a href="{{ route('admin.attendances.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Input Absensi
        </a>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-slate-200 bg-slate-50"><tr>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Tanggal</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Siswa</th>
                    <th class="hidden px-4 py-3 text-left font-semibold text-slate-700 md:table-cell">Kelas</th>
                    <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                    <th class="px-4 py-3 text-right font-semibold text-slate-700">Aksi</th>
                </tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($attendances as $a)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 text-slate-600">{{ $a->attendance_date->format('d M Y') }}</td>
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $a->student->name ?? '-' }}</td>
                        <td class="hidden px-4 py-3 text-slate-600 md:table-cell">{{ $a->classroom->name ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @php
                                $variants = ['present' => 'success', 'sick' => 'warning', 'permission' => 'info', 'absent' => 'danger', 'late' => 'warning'];
                                $labels = ['present' => 'Hadir', 'sick' => 'Sakit', 'permission' => 'Izin', 'absent' => 'Alpha', 'late' => 'Terlambat'];
                            @endphp
                            <x-admin.badge :label="$labels[$a->status] ?? $a->status" :variant="$variants[$a->status] ?? 'default'" />
                        </td>
                        <td class="px-4 py-3 text-right">
                            <form method="POST" action="{{ route('admin.attendances.destroy', $a) }}" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="rounded-lg p-1.5 text-red-500 hover:bg-red-50" title="Hapus">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-16"><x-admin.empty-state title="Tidak ada data absensi" message="Mulai input absensi harian." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($attendances->hasPages())<div class="border-t border-slate-200 px-4 py-3">{{ $attendances->links() }}</div>@endif
    </div>
</x-admin-layout>