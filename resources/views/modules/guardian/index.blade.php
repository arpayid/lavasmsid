<x-admin-layout heading="Data Orang Tua/Wali">
    <div class="mb-6 flex items-center justify-between">
        <form method="GET" class="flex gap-2">
            <x-admin.form-input name="search" placeholder="Cari nama/telepon..." :value="request('search')" class="w-64" />
            <button type="submit" class="rounded-lg bg-primary-600 px-4 py-2 text-sm font-medium text-white">Cari</button>
        </form>
        @can('guardian.create')<a href="{{ route('admin.guardians.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-primary-700"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>Tambah Orang Tua/Wali</a>@endcan
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm"><thead class="border-b bg-slate-50"><tr>
            <th class="px-4 py-3 text-left font-semibold text-slate-700">Nama</th><th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Hubungan</th><th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Telepon</th><th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th><th class="px-4 py-3 text-right font-semibold text-slate-700">Aksi</th>
        </tr></thead>
        <tbody class="divide-y">
            @forelse($guardians as $g)
            <tr class="hover:bg-slate-50">
                <td class="px-4 py-3 font-medium">{{ $g->name }}</td>
                <td class="hidden md:table-cell px-4 py-3 capitalize">{{ $g->relation }}</td>
                <td class="hidden md:table-cell px-4 py-3">{{ $g->phone ?? '-' }}</td>
                <td class="px-4 py-3"><x-admin.badge :label="$g->is_active ? 'Aktif' : 'Nonaktif'" :variant="$g->is_active ? 'success' : 'default'" /></td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('admin.guardians.show', $g) }}" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100">Detail</a>
                    @can('guardian.update')<a href="{{ route('admin.guardians.edit', $g) }}" class="rounded-lg p-1.5 text-slate-500 hover:bg-slate-100">Edit</a>@endcan
                    @can('guardian.delete')
                    <form method="POST" action="{{ route('admin.guardians.destroy', $g) }}" class="inline" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button type="submit" class="rounded-lg p-1.5 text-red-500 hover:bg-red-50">Hapus</button></form>
                    @endcan
                </td>
            </tr>
            @empty <tr><td colspan="5" class="px-4 py-16"><x-admin.empty-state title="Belum ada data orang tua/wali" message="Tambahkan data orang tua/wali baru." /></td></tr> @endforelse
        </tbody></table>
        @if($guardians->hasPages())<div class="px-4 py-3 border-t">{{ $guardians->links() }}</div>@endif
    </div>
</x-admin-layout>
