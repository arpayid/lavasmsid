<x-admin-layout heading="Laporan Jurusan">
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Kode</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Jurusan</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Status</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($departments as $d)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $d->code }}</td>
                    <td class="px-4 py-3 font-medium">{{ $d->name }}</td>
                    <td class="hidden md:table-cell px-4 py-3"><x-admin.badge :label="$d->is_active ? 'Aktif' : 'Nonaktif'" :variant="$d->is_active ? 'success' : 'default'" /></td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-4 py-16 text-center text-slate-500">Tidak ada data jurusan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
