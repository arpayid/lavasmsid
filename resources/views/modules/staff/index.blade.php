<x-admin-layout heading="Data Staff">
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <form method="GET" class="flex gap-2"><x-admin.form-input name="search" placeholder="Cari nama/NIP..." :value="request('search')" class="w-64" />
            <select name="status" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm">
                <option value="">Semua Status</option> <option value="active" @selected(request('status') == 'active')>Aktif</option> <option value="inactive" @selected(request('status') == 'inactive')>Nonaktif</option>
            </select>
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white">Filter</button>
        </form>
        @can('staff.create')<a href="{{ route('admin.staff.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Tambah Staff</a>@endcan
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm"><thead class="border-b bg-slate-50"><tr>
            <th class="px-4 py-3 text-left font-semibold text-slate-700">NIK</th><th class="px-4 py-3 text-left font-semibold text-slate-700">Nama</th><th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Jabatan</th><th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th><th class="px-4 py-3 text-right font-semibold text-slate-700">Aksi</th>
        </tr></thead>
        <tbody class="divide-y">
            @forelse($staff as $s)
            <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-mono text-xs">{{ $s->employee_number ?? '-' }}</td>
                <td class="px-4 py-3 font-medium">{{ $s->name }}</td>
                <td class="hidden md:table-cell px-4 py-3">{{ $s->position ?? '-' }}</td>
                <td class="px-4 py-3"><x-admin.badge :label="$s->status == 'active' ? 'Aktif' : 'Nonaktif'" :variant="$s->status == 'active' ? 'success' : 'default'" /></td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('admin.staff.show', $s) }}" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100">Detail</a>
                    @can('staff.update')<a href="{{ route('admin.staff.edit', $s) }}" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100">Edit</a>@endcan
                    @can('staff.delete')
                    <form method="POST" action="{{ route('admin.staff.destroy', $s) }}" class="inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button type="submit" class="rounded-lg p-1.5 text-red-500 hover:bg-red-50">Hapus</button></form>
                    @endcan
                </td>
            </tr>
            @empty <tr><td colspan="5" class="px-4 py-16"><x-admin.empty-state title="Belum ada staff" message="Tambahkan staff baru." /></td></tr> @endforelse
        </tbody></table>
        @if($staff->hasPages())<div class="px-4 py-3 border-t">{{ $staff->links() }}</div>@endif
    </div>
</x-admin-layout>
