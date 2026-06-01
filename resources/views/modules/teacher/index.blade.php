<x-admin-layout heading="Data Guru">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" class="flex gap-2">
            <x-admin.form-input name="search" placeholder="Cari nama/NIP/NUPTK..." :value="request('search')" class="w-64" />
            <select name="status" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="active" @selected(request('status') == 'active')>Aktif</option>
                <option value="inactive" @selected(request('status') == 'inactive')>Nonaktif</option>
            </select>
            <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white">Filter</button>
        </form>
        @can('teacher.create')
        <a href="{{ route('admin.teachers.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-primary-700">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Guru
        </a>
        @endcan
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">NIP</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Nama</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Telepon</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
                <th class="px-4 py-3 text-right font-semibold text-slate-700">Aksi</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($teachers as $t)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $t->nip ?? '-' }}</td>
                    <td class="px-4 py-3 font-medium">{{ $t->name }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $t->phone ?? '-' }}</td>
                    <td class="px-4 py-3"><x-admin.badge :label="$t->status == 'active' ? 'Aktif' : 'Nonaktif'" :variant="$t->status == 'active' ? 'success' : 'default'" /></td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.teachers.show', $t) }}" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                        @can('teacher.update')<a href="{{ route('admin.teachers.edit', $t) }}" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>@endcan
                        @can('teacher.delete')
                        <form method="POST" action="{{ route('admin.teachers.destroy', $t) }}" class="inline" onsubmit="return confirm('Hapus guru ini?')">@csrf @method('DELETE')<button type="submit" class="rounded-lg p-1.5 text-red-500 hover:bg-red-50"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>
                        @endcan
                    </td>
                </tr>
                @empty <tr><td colspan="5" class="px-4 py-16"><x-admin.empty-state title="Belum ada data guru" message="Mulai tambahkan guru baru." /></td></tr> @endforelse
            </tbody>
        </table>
        @if($teachers->hasPages())<div class="px-4 py-3 border-t">{{ $teachers->links() }}</div>@endif
    </div>
</x-admin-layout>
