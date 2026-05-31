<x-admin-layout heading="Laporan Guru">
    <div class="mb-6">
        <span class="text-sm text-slate-500">Total: <strong>{{ $teachers->count() }}</strong> guru</span>
    </div>
    <div class="overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-slate-200">
        <table class="w-full text-sm">
            <thead class="border-b bg-slate-50"><tr>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">NIP</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Nama</th>
                <th class="hidden md:table-cell px-4 py-3 text-left font-semibold text-slate-700">Telepon</th>
                <th class="px-4 py-3 text-left font-semibold text-slate-700">Status</th>
            </tr></thead>
            <tbody class="divide-y">
                @forelse($teachers as $t)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-mono text-xs">{{ $t->nip ?? '-' }}</td>
                    <td class="px-4 py-3 font-medium">{{ $t->name }}</td>
                    <td class="hidden md:table-cell px-4 py-3">{{ $t->phone ?? '-' }}</td>
                    <td class="px-4 py-3"><x-admin.badge :label="$t->status ?? 'Aktif'" variant="success" /></td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-16 text-center text-slate-500">Tidak ada data guru.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-admin-layout>
