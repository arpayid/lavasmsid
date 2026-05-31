<x-admin-layout heading="Tahun Ajaran">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" action="{{ route('admin.academic-years.index') }}" class="flex gap-2">
            <x-admin.form-input name="search" placeholder="Cari tahun ajaran..." :value="request('search')" class="w-72" />
            <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">
                Cari
            </button>
        </form>
        @can('academic.create')
        <a href="{{ route('admin.academic-years.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Tahun Ajaran
        </a>
        @endcan
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-slate-200 bg-slate-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-slate-700">Tahun Ajaran</th>
                        <th class="hidden px-4 py-3 text-left font-semibold text-slate-700 md:table-cell">Mulai</th>
                        <th class="hidden px-4 py-3 text-left font-semibold text-slate-700 md:table-cell">Selesai</th>
                        <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                        <th class="px-4 py-3 text-right font-semibold text-slate-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($academicYears as $ay)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-4 py-3 font-medium text-slate-900">{{ $ay->name }}</td>
                        <td class="hidden px-4 py-3 text-slate-600 md:table-cell">{{ $ay->start_date->format('d M Y') }}</td>
                        <td class="hidden px-4 py-3 text-slate-600 md:table-cell">{{ $ay->end_date->format('d M Y') }}</td>
                        <td class="px-4 py-3">
                            <x-admin.badge :label="$ay->is_active ? 'Aktif' : 'Nonaktif'" :variant="$ay->is_active ? 'success' : 'default'" />
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.academic-years.show', $ay) }}" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100" title="Detail">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('admin.academic-years.edit', $ay) }}" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100" title="Edit">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('admin.academic-years.destroy', $ay) }}" onsubmit="return confirm('Hapus tahun ajaran ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="rounded-lg p-1.5 text-red-500 hover:bg-red-50" title="Hapus">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-16"><x-admin.empty-state title="Tidak ada data tahun ajaran" message="Mulai tambahkan tahun ajaran baru." /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($academicYears->hasPages())
        <div class="border-t border-slate-200 px-4 py-3">{{ $academicYears->links() }}</div>
        @endif
    </div>
</x-admin-layout>
